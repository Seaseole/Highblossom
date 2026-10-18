/**
 * Where the wizard parks an in-progress booking so a refresh does not lose it.
 *
 * Exposed globally because the confirmation page has to clear it: reaching that
 * page is the only reliable proof the booking actually landed, and leaving the
 * draft behind reopens a finished form on the next visit.
 */
window.bookingDraftKey = 'highblossom-booking-draft';

/**
 * Public booking wizard.
 *
 * Owns the four steps, the calendar, and the slot picker. Availability is never
 * decided here: the server hands over which days have room and which times are
 * free, and this only draws what it said. That keeps a tampered-with client from
 * ever offering a slot the submission will reject.
 */
window.bookingWizard = function () {
    return {
        // Wizard
        currentStep: 1,
        totalSteps: 4,
        visited: {},
        showErrors: false,
        isSubmitting: false,

        // Step 1 — vehicle and service
        serviceType: '',
        damageType: '',
        vehicleMake: '',
        vehicleModel: '',
        vehicleYear: '',
        location: '',
        clientAddress: '',

        // Step 2 — date and time
        selectedDate: '',
        selectedTime: '',
        scheduledAt: '',
        slots: [],
        slotsState: 'idle',
        autoPick: false,
        availableCount: 0,
        durationMinutes: 60,
        earliest: null,

        // Calendar
        months: [],
        monthsByKey: {},
        activeMonthIndex: 0,
        maxMonthIndex: 0,
        baselineMonth: '',
        calendarOpen: false,
        focusDate: '',
        stripDates: [],

        // Step 3 — your details
        clientName: '',
        clientEmail: '',
        clientPhone: '',
        prefersWhatsapp: false,
        vehicleDetails: '',

        // Labels injected from PHP so nothing here needs its own translations.
        reasonLabels: {},
        periodLabels: {},
        availabilityLabels: {},
        optionLabels: {},
        stepLabels: [],
        slotCountLabels: {},
        stepOfTemplate: '',

        draftKey: window.bookingDraftKey,

        init() {
            const root = this.$root;

            this.serviceType = root.dataset.serviceType || '';
            this.damageType = root.dataset.damageType || '';
            this.vehicleMake = root.dataset.vehicleMake || '';
            this.vehicleModel = root.dataset.vehicleModel || '';
            this.vehicleYear = root.dataset.vehicleYear || '';
            this.location = root.dataset.location || '';
            this.clientAddress = root.dataset.clientAddress || '';
            this.clientName = root.dataset.clientName || '';
            this.clientEmail = root.dataset.clientEmail || '';
            this.clientPhone = root.dataset.clientPhone || '';
            this.vehicleDetails = root.dataset.vehicleDetails || '';
            this.prefersWhatsapp = root.dataset.prefersWhatsapp === '1';
            this.scheduledAt = root.dataset.scheduledAt || '';
            this.currentStep = parseInt(root.dataset.initialStep, 10) || 1;
            this.reasonLabels = this.readJson(root.dataset.reasonLabels);
            this.periodLabels = this.readJson(root.dataset.periodLabels);
            this.availabilityLabels = this.readJson(root.dataset.availabilityLabels);
            this.stripDates = this.readJson(root.dataset.stripDates);

            this.months = this.readJson(root.dataset.months);
            this.months.forEach((month) => (this.monthsByKey[month.month] = month));
            this.baselineMonth = root.dataset.baselineMonth || '';
            this.optionLabels = this.readJson(root.dataset.optionLabels);
            this.stepLabels = this.readJson(root.dataset.stepLabels);
            this.slotCountLabels = this.readJson(root.dataset.slotCountLabels);
            this.stepOfTemplate = root.dataset.stepOfTemplate || '';
            this.maxMonthIndex = Math.max(
                this.months.length - 1,
                (parseInt(root.dataset.totalMonths, 10) || this.months.length) - 1
            );

            if (! this.scheduledAt && this.restoreDraft()) {
                this.currentStep = this.clampStep(parseInt(localStorage.getItem(this.draftKey + ':step'), 10) || this.currentStep);
            }

            if (this.scheduledAt) {
                const [date, time] = this.scheduledAt.split('T');
                this.selectedDate = date || '';
                this.selectedTime = time ? time.substring(0, 5) : '';
                this.refreshMonth(this.selectedDate);
                this.fetchSlots(this.selectedDate);
            }

            this.focusDate = this.selectedDate || this.firstSelectableDate() || '';

            this.$watch('currentStep', () => {
                this.visited[this.currentStep] = true;
                this.saveDraft();
                window.scrollTo({ top: root.getBoundingClientRect().top + window.scrollY - 90, behavior: 'smooth' });
            });

            this.$watch('serviceType', () => this.onContextChange());
            this.$watch('location', () => this.onContextChange());

            this.visited[this.currentStep] = true;
        },

        readJson(raw) {
            try {
                return JSON.parse(raw || '[]');
            } catch (error) {
                return [];
            }
        },

        /**
         * Re-read the calendar whenever the job changes shape.
         *
         * A longer service eats more of the day, and mobile units are a separate
         * capacity pool, so "open" is not a property of a date on its own.
         */
        onContextChange() {
            if (! this.selectedDate) {
                return;
            }

            this.refreshMonth(this.selectedDate);
            this.fetchSlots(this.selectedDate);
        },

        // ---------------------------------------------------------------- steps

        stepFields(step) {
            if (step === 1) {
                return [this.serviceType, this.damageType, this.vehicleMake, this.vehicleModel, this.vehicleYear, this.location];
            }

            if (step === 2) {
                return [this.scheduledAt];
            }

            if (step === 3) {
                return [this.clientName, this.clientEmail, this.clientPhone];
            }

            return [];
        },

        /**
         * Whether a step is complete enough to leave.
         *
         * Mirrors the server rather than replacing it: an address is only demanded
         * for mobile fittings, exactly as StoreBookingRequest decides.
         */
        isStepValid(step) {
            if (! this.stepFields(step).every((value) => String(value).trim() !== '')) {
                return false;
            }

            if (step === 1 && this.location === 'mobile' && this.clientAddress.trim() === '') {
                return false;
            }

            return true;
        },

        canLeave(step) {
            return this.isStepValid(step);
        },

        goTo(step) {
            const target = this.clampStep(step);

            // Only completed steps are walkable backwards; locked ones are not.
            if (target > this.currentStep && ! this.isStepValid(this.currentStep)) {
                this.showErrors = true;

                return;
            }

            this.showErrors = false;
            this.currentStep = target;
        },

        nextStep() {
            if (this.isStepValid(this.currentStep)) {
                this.goTo(this.currentStep + 1);
            } else {
                this.showErrors = true;
            }
        },

        prevStep() {
            this.goTo(this.currentStep - 1);
        },

        clampStep(step) {
            return Math.min(this.totalSteps, Math.max(1, step));
        },

        get canSubmit() {
            return [1, 2, 3].every((step) => this.isStepValid(step));
        },

        // ------------------------------------------------------------- calendar

        get activeMonth() {
            return this.months[this.activeMonthIndex] || null;
        },

        get monthLabel() {
            return this.activeMonth ? this.activeMonth.label : '';
        },

        get activeDays() {
            return this.activeMonth ? this.activeMonth.days : [];
        },

        get leadingBlanks() {
            return this.activeMonth ? this.activeMonth.leading_blanks : 0;
        },

        get availableLabel() {
            if (! this.activeMonth) {
                return '';
            }

            const count = this.activeMonth.available_count;

            return count === 1
                ? this.availabilityLabels.singular
                : String(this.availabilityLabels.plural || '').replace(':count', count).replace('%count%', count);
        },

        monthAt(index) {
            return this.months[index] || null;
        },

        firstSelectableDate() {
            for (let index = 0; index <= this.maxMonthIndex; index++) {
                const month = this.months[index];

                if (month) {
                    const day = month.days.find((entry) => entry.selectable);

                    if (day) {
                        return day.date;
                    }
                }
            }

            return '';
        },

        /**
         * True when the calendar should draw this date as the focused cell.
         *
         * Focus lives on a date rather than a DOM node so that turning the month
         * keeps the keyboard cursor where the customer left it.
         */
        isFocused(date) {
            return this.focusDate === date;
        },

        tabIndexFor(date) {
            if (! this.focusDate) {
                return date === this.firstSelectableDate() ? 0 : -1;
            }

            return date === this.focusDate ? 0 : -1;
        },

        moveFocus(date) {
            if (! date) {
                return;
            }

            this.focusDate = date;
            this.$nextTick(() => {
                const cell = this.$refs.calendarGrid?.querySelector(`[data-date="${date}"]`);

                if (cell) {
                    cell.focus();
                }
            });
        },

        shiftFocus(days) {
            const base = this.parseDate(this.focusDate) || this.parseDate(this.firstSelectableDate());

            if (! base) {
                return;
            }

            base.setDate(base.getDate() + days);
            this.focusOn(base);
        },

        shiftWeek(days) {
            const base = this.parseDate(this.focusDate);

            if (! base) {
                return;
            }

            base.setDate(base.getDate() + days);
            this.focusOn(base);
        },

        focusOn(date) {
            const iso = this.toIso(date);
            const key = iso.slice(0, 7);
            const index = this.monthIndexOf(key);

            if (index === null) {
                return;
            }

            this.activeMonthIndex = index;
            this.ensureMonth(index);
            this.moveFocus(iso);
        },

        monthIndexOf(key) {
            for (let index = 0; index <= this.maxMonthIndex; index++) {
                if (this.monthKeyAt(index) === key) {
                    return index;
                }
            }

            return null;
        },

        monthKeyAt(index) {
            const month = this.months[index];

            if (month) {
                return month.month;
            }

            const base = this.parseDate(this.baselineMonth);

            if (! base) {
                return null;
            }

            base.setMonth(base.getMonth() + index);

            return `${base.getFullYear()}-${String(base.getMonth() + 1).padStart(2, '0')}`;
        },

        onCalendarKeydown(event, date) {
            const keys = {
                ArrowLeft: -1,
                ArrowRight: 1,
                ArrowUp: -7,
                ArrowDown: 7,
            };

            if (event.key in keys) {
                event.preventDefault();
                this.shiftFocus(keys[event.key]);

                return;
            }

            if (event.key === 'Home') {
                event.preventDefault();
                this.shiftWeek(-this.weekdayIndex(this.focusDate));
            } else if (event.key === 'End') {
                event.preventDefault();
                this.shiftWeek(6 - this.weekdayIndex(this.focusDate));
            } else if (event.key === 'PageUp') {
                event.preventDefault();
                this.prevMonth();
            } else if (event.key === 'PageDown') {
                event.preventDefault();
                this.nextMonth();
            }
        },

        weekdayIndex(iso) {
            const date = this.parseDate(iso);

            if (! date) {
                return 0;
            }

            return (date.getDay() + 6) % 7;
        },

        prevMonth() {
            if (this.activeMonthIndex > 0) {
                this.activeMonthIndex--;
                this.ensureMonth(this.activeMonthIndex);
                this.reanchorFocus();
            }
        },

        nextMonth() {
            if (this.activeMonthIndex < this.maxMonthIndex) {
                this.activeMonthIndex++;
                this.ensureMonth(this.activeMonthIndex);
                this.reanchorFocus();
            }
        },

        reanchorFocus() {
            const month = this.activeMonth;

            if (! month) {
                return;
            }

            const stillThere = month.days.some((day) => day.date === this.focusDate);

            if (! stillThere) {
                const first = month.days.find((day) => day.selectable);
                this.focusDate = first ? first.date : (month.days[0]?.date ?? this.focusDate);
            }
        },

        /**
         * Fetch a month the calendar has not drawn yet.
         *
         * The first three months arrive rendered with the page; anything further is
         * pulled on demand so the initial payload stays small.
         */
        ensureMonth(index) {
            const key = this.monthKeyAt(index);

            if (! key || this.months[index]) {
                return;
            }

            fetch(`/api/bookings/calendar?month=${encodeURIComponent(key)}${this.contextQuery()}`)
                .then((response) => (response.ok ? response.json() : Promise.reject(new Error('calendar lookup failed'))))
                .then((payload) => {
                    this.months[index] = payload;
                    this.monthsByKey[payload.month] = payload;
                })
                .catch(() => {
                    this.months[index] = { month: key, label: key, leading_blanks: 0, days: [], available_count: 0, open_count: 0 };
                });
        },

        /**
         * Drop a cached month and re-read it against the current service and location.
         *
         * Both maps have to go: `findDay` consults the key map first, so leaving a
         * stale entry there would keep a now-unbookable day looking pickable.
         */
        refreshMonth(iso) {
            const key = String(iso || '').slice(0, 7);
            const index = this.monthIndexOf(key);

            if (index === null) {
                return;
            }

            this.months[index] = null;
            delete this.monthsByKey[key];
            this.activeMonthIndex = index;
            this.ensureMonth(index);
        },

        contextQuery() {
            const params = new URLSearchParams();

            if (this.serviceType) {
                params.set('service', this.serviceType);
            }

            if (this.location) {
                params.set('location', this.location);
            }

            const query = params.toString();

            return query ? `&${query}` : '';
        },

        parseDate(iso) {
            if (! iso) {
                return null;
            }

            const [year, month, day] = String(iso).split('-').map(Number);
            const date = new Date(year, (month || 1) - 1, day || 1);

            return Number.isNaN(date.getTime()) ? null : date;
        },

        toIso(date) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        },

        // ---------------------------------------------------------------- slots

        selectDate(date, openPicker = false) {
            const month = this.findDay(date);

            if (! month || ! month.selectable) {
                return;
            }

            if (this.selectedDate === date) {
                return;
            }

            this.selectedDate = date;
            this.selectedTime = '';
            this.scheduledAt = '';
            this.focusDate = date;

            if (openPicker) {
                this.calendarOpen = true;
            }

            this.fetchSlots(date);
        },

        findDay(date) {
            for (const month of Object.values(this.monthsByKey)) {
                const day = month?.days?.find((entry) => entry.date === date);

                if (day) {
                    return day;
                }
            }

            for (const month of this.months) {
                const day = month?.days?.find((entry) => entry.date === date);

                if (day) {
                    return day;
                }
            }

            return null;
        },

        dayState(date) {
            const day = this.findDay(date);

            if (! day) {
                return { selectable: false, reason: 'loading' };
            }

            return day;
        },

        fetchSlots(date) {
            if (! date) {
                return;
            }

            this.slotsState = 'loading';
            this.slots = [];
            this.availableCount = 0;
            this.earliest = null;

            fetch(`/api/bookings/availability?date=${encodeURIComponent(date)}${this.contextQuery()}`)
                .then((response) => {
                    if (! response.ok) {
                        throw new Error(`Availability lookup failed with status ${response.status}`);
                    }

                    return response.json();
                })
                .then((data) => {
                    this.slots = data.slots || [];
                    this.earliest = data.earliest || null;
                    this.durationMinutes = data.duration_minutes || 60;
                    this.availableCount = data.available_count || 0;

                    if (data.closed) {
                        this.slotsState = 'closed';
                    } else if (this.availableCount === 0) {
                        this.slotsState = 'empty';
                    } else {
                        this.slotsState = 'ready';
                    }

                    if (this.autoPick) {
                        this.autoPick = false;
                        const first = this.slots.find((slot) => slot.available);

                        if (first) {
                            this.selectSlot(first);
                        }
                    }

                    this.saveDraft();
                })
                .catch(() => {
                    this.autoPick = false;
                    this.slotsState = 'error';
                });
        },

        selectSlot(slot) {
            if (! slot.available) {
                return;
            }

            this.selectedTime = slot.time;
            this.scheduledAt = `${this.selectedDate}T${slot.time}:00`;
            this.showErrors = false;
            this.saveDraft();
        },

        reasonFor(slot) {
            return this.reasonLabels[slot.status] || '';
        },

        get slotGroups() {
            const groups = [
                { key: 'morning', slots: this.slots.filter((slot) => Number(slot.time.slice(0, 2)) < 12) },
                { key: 'afternoon', slots: this.slots.filter((slot) => Number(slot.time.slice(0, 2)) >= 12) },
            ];

            return groups.filter((group) => group.slots.length > 0);
        },

        get earliestLabel() {
            return this.earliest ? `${this.earliest.day_label} - ${this.earliest.time_label}` : '';
        },

        get slotCountLabel() {
            const template = this.availableCount === 1
                ? this.slotCountLabels.one
                : this.slotCountLabels.other;

            return String(template || '').replace(':count', this.availableCount);
        },

        get stepOfLabel() {
            return String(this.stepOfTemplate)
                .replace(':step', this.currentStep)
                .replace(':total', this.totalSteps);
        },

        /**
         * Jump to the first day with room and take its earliest slot.
         *
         * The slot has to be chosen after the fetch resolves, hence the flag rather
         * than a lookup here.
         */
        jumpToEarliest() {
            const date = this.firstSelectableDate();

            if (! date) {
                return;
            }

            this.calendarOpen = true;
            this.autoPick = true;

            if (this.selectedDate === date) {
                this.fetchSlots(date);

                return;
            }

            this.selectDate(date);
        },

        // ------------------------------------------------------------ quick pick

        quickPick(offset) {
            const date = new Date();
            date.setDate(date.getDate() + offset);

            if (! this.selectNearest(this.toIso(date))) {
                this.jumpToEarliest();
            }
        },

        /**
         * Pick the requested day, else the next one already known to have room.
         *
         * "Tomorrow" is not always bookable, and a chip that silently does nothing is
         * worse than one that lands the customer on the next real option. Only days
         * already loaded are considered, so this never waits on a fetch.
         */
        selectNearest(iso) {
            let cursor = this.parseDate(iso);

            if (! cursor) {
                return false;
            }

            for (let attempt = 0; attempt < 14; attempt++) {
                const candidate = this.toIso(cursor);

                if (this.findDay(candidate)?.selectable) {
                    this.selectDate(candidate);

                    return true;
                }

                cursor.setDate(cursor.getDate() + 1);
            }

            return false;
        },

        get thisWeekDates() {
            const dates = [];
            const cursor = new Date();

            for (let offset = 0; offset < 7; offset++) {
                dates.push(this.toIso(cursor));
                cursor.setDate(cursor.getDate() + 1);
            }

            return dates;
        },

        bookThisWeek() {
            for (const date of this.thisWeekDates) {
                if (this.findDay(date)?.selectable) {
                    this.selectDate(date);
                    this.calendarOpen = true;

                    return;
                }
            }

            this.jumpToEarliest();
        },

        // --------------------------------------------------------------- summary

        get selectedDateLabel() {
            const date = this.parseDate(this.selectedDate);

            if (! date) {
                return '';
            }

            return date.toLocaleDateString(undefined, { weekday: 'short', day: 'numeric', month: 'short' });
        },

        get selectedSlot() {
            return this.slots.find((slot) => slot.time === this.selectedTime) || null;
        },

        get timeLabel() {
            return this.selectedSlot ? this.selectedSlot.label : '';
        },

        get endsLabel() {
            return this.selectedSlot ? this.selectedSlot.end_label : '';
        },

        get serviceLabel() {
            return this.labelFor('services', this.serviceType);
        },

        get damageLabel() {
            return this.labelFor('damages', this.damageType);
        },

        get locationLabel() {
            return this.labelFor('locations', this.location);
        },

        get vehicleLabel() {
            return [this.vehicleYear, this.vehicleMake, this.vehicleModel].filter(Boolean).join(' ');
        },

        /**
         * Resolve a display label from the maps rendered by Blade.
         */
        labelFor(group, key) {
            return (this.optionLabels?.[group] || {})[key] || '';
        },

        // ----------------------------------------------------------------- draft

        saveDraft() {
            try {
                const draft = {
                    serviceType: this.serviceType,
                    damageType: this.damageType,
                    vehicleMake: this.vehicleMake,
                    vehicleModel: this.vehicleModel,
                    vehicleYear: this.vehicleYear,
                    location: this.location,
                    clientAddress: this.clientAddress,
                    clientName: this.clientName,
                    clientEmail: this.clientEmail,
                    clientPhone: this.clientPhone,
                    prefersWhatsapp: this.prefersWhatsapp,
                    vehicleDetails: this.vehicleDetails,
                    selectedDate: this.selectedDate,
                    scheduledAt: this.scheduledAt,
                };

                localStorage.setItem(this.draftKey, JSON.stringify(draft));
                localStorage.setItem(this.draftKey + ':step', String(this.currentStep));
            } catch (error) {
                // Private-browsing modes reject localStorage; the wizard still works.
            }
        },

        restoreDraft() {
            try {
                const raw = localStorage.getItem(this.draftKey);

                if (! raw) {
                    return false;
                }

                const draft = JSON.parse(raw);

                if (! draft || typeof draft !== 'object') {
                    return false;
                }

                Object.entries(draft).forEach(([key, value]) => {
                    if (key in this && value !== null && typeof this[key] !== 'object') {
                        this[key] = value;
                    }
                });

                return Boolean(draft.selectedDate || draft.clientName || draft.serviceType);
            } catch (error) {
                return false;
            }
        },

        clearDraft() {
            try {
                localStorage.removeItem(this.draftKey);
                localStorage.removeItem(this.draftKey + ':step');
            } catch (error) {
                // Nothing to do if storage is unavailable.
            }
        },

        discardDraft() {
            this.clearDraft();
            window.location.href = window.location.pathname;
        },
    };
};
