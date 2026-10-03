window.bookingWizard = function () {
    return {
        currentStep: 1,
        activeMonthIndex: 0,
        totalMonths: 0,
        monthLabels: [],
        stripDates: [],
        showCalendar: false,
        scheduledAt: '',
        location: '',
        clientName: '',
        clientEmail: '',
        clientPhone: '',
        vehicleDetails: '',
        clientAddress: '',
        slots: [],
        slotsState: 'idle',
        earliest: null,
        reasonLabels: {},
        periodLabels: {},
        selectedTime: '',
        selectedDate: '',
        isSubmitting: false,

        init() {
            const root = this.$root;
            this.scheduledAt = root.dataset.scheduledAt || '';
            this.location = root.dataset.location || '';
            this.clientName = root.dataset.clientName || '';
            this.clientEmail = root.dataset.clientEmail || '';
            this.clientPhone = root.dataset.clientPhone || '';
            this.vehicleDetails = root.dataset.vehicleDetails || '';
            this.clientAddress = root.dataset.clientAddress || '';
            this.totalMonths = parseInt(root.dataset.monthsCount, 10) || 0;
            this.monthLabels = JSON.parse(root.dataset.monthsLabels || '[]');
            this.stripDates = JSON.parse(root.dataset.stripDates || '[]');
            this.reasonLabels = JSON.parse(root.dataset.reasonLabels || '{}');
            this.periodLabels = JSON.parse(root.dataset.periodLabels || '{}');
            this.currentStep = parseInt(root.dataset.initialStep, 10) || 1;

            if (! this.scheduledAt) {
                return;
            }

            const parts = this.scheduledAt.split('T');
            this.selectedDate = parts[0] || '';
            this.selectedTime = parts[1] ? parts[1].substring(0, 5) : '';

            if (! this.selectedDate) {
                return;
            }

            this.showCalendar = ! this.stripDates.includes(this.selectedDate);
            this.fetchSlots(this.selectedDate);
        },

        nextMonth() {
            if (this.activeMonthIndex < this.totalMonths - 1) {
                this.activeMonthIndex++;
            }
        },

        prevMonth() {
            if (this.activeMonthIndex > 0) {
                this.activeMonthIndex--;
            }
        },

        fetchSlots(date) {
            this.slotsState = 'loading';
            this.slots = [];
            this.earliest = null;

            fetch(`/api/bookings/availability?date=${encodeURIComponent(date)}`, {
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then((response) => {
                    if (! response.ok) {
                        throw new Error(`Availability lookup failed with status ${response.status}`);
                    }

                    return response.json();
                })
                .then((data) => {
                    this.slots = data.slots || [];
                    this.earliest = data.earliest || null;

                    if (data.closed) {
                        this.slotsState = 'closed';
                    } else if (! this.slots.some((slot) => slot.available)) {
                        this.slotsState = 'empty';
                    } else {
                        this.slotsState = 'ready';
                    }
                })
                .catch(() => {
                    this.slotsState = 'error';
                });
        },

        selectDate(date) {
            if (this.selectedDate === date) {
                return;
            }

            this.selectedDate = date;
            this.selectedTime = '';
            this.scheduledAt = '';
            this.fetchSlots(date);
        },

        selectSlot(slot) {
            if (! slot.available) {
                return;
            }

            this.selectedTime = slot.time;
            this.scheduledAt = `${this.selectedDate}T${slot.time}:00`;
        },

        reasonFor(slot) {
            return this.reasonLabels[slot.status] || '';
        },

        get morningSlots() {
            return this.slots.filter((slot) => parseInt(slot.time.slice(0, 2), 10) < 12);
        },

        get afternoonSlots() {
            return this.slots.filter((slot) => parseInt(slot.time.slice(0, 2), 10) >= 12);
        },

        get slotGroups() {
            return [
                { key: 'morning', slots: this.morningSlots },
                { key: 'afternoon', slots: this.afternoonSlots },
            ].filter((group) => group.slots.length > 0);
        },

        get earliestLabel() {
            if (! this.earliest) {
                return '';
            }

            return `${this.earliest.day_label} - ${this.earliest.time_label}`;
        },

        get selectedDateLabel() {
            if (! this.selectedDate) {
                return '';
            }

            return new Date(`${this.selectedDate}T00:00:00`).toLocaleDateString(undefined, {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
            });
        },

        get selectedSlot() {
            return this.slots.find((slot) => slot.time === this.selectedTime) || null;
        },

        get summary() {
            if (! this.scheduledAt) {
                return '';
            }

            const time = this.selectedSlot ? this.selectedSlot.label : this.selectedTime;

            return `${this.selectedDateLabel} at ${time}`;
        },

        get canProceed() {
            if (this.currentStep === 1) {
                return this.scheduledAt !== '';
            }

            if (this.currentStep === 2) {
                return (
                    this.clientName.trim() !== '' &&
                    this.clientEmail.trim() !== '' &&
                    this.clientPhone.trim() !== ''
                );
            }

            return true;
        },

        get monthLabel() {
            return this.monthLabels[this.activeMonthIndex] || '';
        },

        get canSubmit() {
            return (
                this.scheduledAt !== '' &&
                this.location !== '' &&
                this.clientName.trim() !== '' &&
                this.clientEmail.trim() !== '' &&
                this.clientPhone.trim() !== '' &&
                this.vehicleDetails.trim() !== '' &&
                (this.location !== 'mobile' || this.clientAddress.trim() !== '')
            );
        },

        nextStep() {
            if (this.currentStep < 3 && this.canProceed) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        submitForm() {
            if (! this.canSubmit || this.isSubmitting) {
                return;
            }

            this.isSubmitting = true;
            this.$refs.form.submit();
        },
    };
};
