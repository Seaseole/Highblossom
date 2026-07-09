window.bookingWizard = function () {
    return {
        currentStep: 1,
        activeMonthIndex: 0,
        totalMonths: 0,
        monthLabels: [],
        scheduledAt: '',
        location: '',
        clientName: '',
        clientEmail: '',
        clientPhone: '',
        vehicleDetails: '',
        clientAddress: '',
        slots: [],
        loadingSlots: false,
        selectedTime: '',
        selectedDate: '',
        hasError: false,
        isSubmitting: false,

        init() {
            const form = this.$root;
            this.scheduledAt = form.dataset.scheduledAt || '';
            this.location = form.dataset.location || '';
            this.clientName = form.dataset.clientName || '';
            this.clientEmail = form.dataset.clientEmail || '';
            this.clientPhone = form.dataset.clientPhone || '';
            this.vehicleDetails = form.dataset.vehicleDetails || '';
            this.clientAddress = form.dataset.clientAddress || '';
            this.hasError = form.dataset.hasError === 'true';
            this.totalMonths = parseInt(form.dataset.monthsCount, 10) || 0;
            this.monthLabels = JSON.parse(form.dataset.monthsLabels || '[]');

            if (this.scheduledAt) {
                const parts = this.scheduledAt.split('T');
                this.selectedDate = parts[0] || '';
                this.selectedTime = parts[1] ? parts[1].substring(0, 5) : '';

                if (this.selectedDate) {
                    this.fetchSlots(this.selectedDate);
                }
            }
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
            this.loadingSlots = true;
            this.slots = [];

            fetch(`/api/bookings/availability?date=${encodeURIComponent(date)}`, {
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    this.slots = data || [];
                    this.loadingSlots = false;
                })
                .catch(() => {
                    this.loadingSlots = false;
                });
        },

        selectDate(date) {
            this.selectedDate = date;
            this.selectedTime = '';
            this.scheduledAt = '';
            this.fetchSlots(date);
        },

        selectSlot(time) {
            this.selectedTime = time;
            this.scheduledAt = `${this.selectedDate}T${time}:00`;
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
