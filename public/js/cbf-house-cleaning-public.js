jQuery(document).ready(function ($) {
	
const { createApp, ref, computed, onMounted } = Vue;

createApp({
	setup() {
		// Predefined locations
	
         
		// Available time slots
		const availableTimeSlots = [
			{ value: "morning", label: "Morning (8AM - 12PM)" },
			{ value: "afternoon", label: "Afternoon (12PM - 4PM)" },
			{ value: "evening", label: "Evening (4PM - 8PM)" }
		];
		const pricing=locationAjax.pricing
		// Form steps
		const steps = ['Location', 'Service', 'Schedule', 'Your Info', 'Summary'];
		const step = ref(0);
		
		// Current date and max date (60 days from now)
		const today = new Date();
		const todayStr = today.toISOString().split('T')[0];
		const maxDate = new Date();
		maxDate.setDate(maxDate.getDate() + 60);
		const maxDateFormatted = maxDate.toISOString().split('T')[0];
		
		// Form data
		const formData = ref({
			propertyType: '',
			squareFeet: 50,
			serviceType: 'basic',
			frequency: 'once',
			date: todayStr,
			timeSlot: '',
			specialRequests: '',
			fullName: '',
			email: '',
			phone: '',
			location: '',
			address: '',
			paymentMethod: 'invoice'
		});

		// Error messages
		const errors = ref({
			fullName: '',
			email: '',
			phone: '',
			location: '',
			address: '',
			paymentMethod: ''
		});
		
		// Calendar data and functions
		const calendar = ref({
		dayHeaders: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		currentYear: today.getFullYear(),
		currentMonth: today.getMonth(),
		displayedMonths: [],
		// Static booked dates in simple formats
		bookedDates: [
			
		],
		// Static off days (holidays and weekends)
		offDays: [
			
		]
	});
	const predefinedLocations = ref([]);
	onMounted(() => {
		fetch(locationAjax.ajax_url, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: new URLSearchParams({
				action: 'get_predefined_locations',
				_ajax_nonce: locationAjax.nonce
			})
		})
		.then(res => res.json())
		.then(data => {
			if (data.success) {
				console.log(data.data);
				predefinedLocations.value = data.data.locations;
				calendar.value.offDays=data.data.off_days
				calendar.value.bookedDates=data.data.all_dates
				initializeCalendar();

			} else {
				console.error('Failed to load locations');
			}
		});
	});
	// Helper function to normalize date strings
	const normalizeDateString = (dateStr) => {
		// Convert to Date object then to YYYY-MM-DD format
		const date = new Date(dateStr);
		if (isNaN(date.getTime())) {
			console.error('Invalid date:', dateStr);
			return null;
		}
		
		const year = date.getFullYear();
		const month = String(date.getMonth() + 1).padStart(2, '0');
		const day = String(date.getDate()).padStart(2, '0');
		return `${year}-${month}-${day}`;
	};

		// Initialize calendar
		const initializeCalendar = () => {
			calendar.value.displayedMonths = generateDisplayedMonths(calendar.value.currentYear, calendar.value.currentMonth);
		};

		// Generate displayed months (3 months at a time)
		const generateDisplayedMonths = (year, month) => {
			const months = [];
			for (let i = 0; i < 3; i++) {
				const currentMonth = (month + i) % 12;
				const currentYear = year + Math.floor((month + i) / 12);
				months.push(generateMonthData(currentYear, currentMonth));
			}
			return months;
		};

	// Replace the generateMonthData function with this updated version
const generateMonthData = (year, month) => {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    
    const firstDayOfWeek = firstDay.getDay();
    const lastDayOfWeek = lastDay.getDay();
    
    const daysFromPrevMonth = firstDayOfWeek;
    const daysFromNextMonth = 6 - lastDayOfWeek;
    
    const days = [];
    
    // Previous month days
    const prevMonth = month === 0 ? 11 : month - 1;
    const prevYear = month === 0 ? year - 1 : year;
    const prevMonthLastDay = new Date(year, month, 0).getDate();
    
    for (let i = daysFromPrevMonth - 1; i >= 0; i--) {
        const day = prevMonthLastDay - i;
        // Fixed date creation - use local date string format
        const date = new Date(prevYear, prevMonth, day);
        const dateStr = formatLocalDateString(date);
        
        days.push({
            dayNumber: day,
            date: dateStr,
            isCurrentMonth: false,
            isToday: isToday(date),
            isBooked: isDateBooked(dateStr),
            isOffDay: isDateOffDay(dateStr),
            isAvailable: isDateAvailable(dateStr)
        });
    }
    
    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        // Fixed date creation - use local date string format
        const dateStr = formatLocalDateString(date);
        
        days.push({
            dayNumber: day,
            date: dateStr,
            isCurrentMonth: true,
            isToday: isToday(date),
            isBooked: isDateBooked(dateStr),
            isOffDay: isDateOffDay(dateStr),
            isAvailable: isDateAvailable(dateStr)
        });
    }
    
    // Next month days
    const nextMonth = month === 11 ? 0 : month + 1;
    const nextYear = month === 11 ? year + 1 : year;
    
    for (let day = 1; day <= daysFromNextMonth; day++) {
        const date = new Date(nextYear, nextMonth, day);
        // Fixed date creation - use local date string format
        const dateStr = formatLocalDateString(date);
        
        days.push({
            dayNumber: day,
            date: dateStr,
            isCurrentMonth: false,
            isToday: isToday(date),
            isBooked: isDateBooked(dateStr),
            isOffDay: isDateOffDay(dateStr),
            isAvailable: isDateAvailable(dateStr)
        });
    }
    
    return {
        name: new Date(year, month, 1).toLocaleString('default', { month: 'long' }),
        year: year,
        month: month,
        days: days,
        key: `${year}-${month}`
    };
};

// Helper function to format date in YYYY-MM-DD without time zone issues
const formatLocalDateString = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

// Also update the isToday function to use the same date format
const isToday = (date) => {
    const today = new Date();
    return date.getDate() === today.getDate() && 
           date.getMonth() === today.getMonth() && 
           date.getFullYear() === today.getFullYear();
};
		// Check if a date is booked
		const isDateBooked = (dateStr) => {
			return calendar.value.bookedDates.includes(dateStr);
		};
		
		// Check if a date is an off day
		const isDateOffDay = (dateStr) => {
			const date = new Date(dateStr);
			const dayOfWeek = date.getDay();
			
			// // Weekends are off days
			if ( dayOfWeek === 0) return true;
			
			// Also check our offDays array
			return calendar.value.offDays.includes(dateStr);
		};
		
		// Check if a date is available
		const isDateAvailable = (dateStr) => {
			const date = new Date(dateStr);
			const dayOfWeek = date.getDay();
			
			// Only weekdays can be available
			if ( dayOfWeek === 0) return false;
			
			// Not booked and not an off day
			return !isDateBooked(dateStr) && !isDateOffDay(dateStr);
		};
		
		// Check if a date is selected
		const isDateSelected = (dateStr) => {
			return formData.value.date === dateStr;
		};
		
		// Select a date from the calendar
		const selectCalendarDate = (day) => {
			if (!day.isCurrentMonth || day.isBooked || day.isOffDay) return;
			formData.value.date = day.date;
		};
		
		// Format the selected date for display
		const formatSelectedDate = (dateStr) => {
			const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
			return new Date(dateStr).toLocaleDateString('en-US', options);
		};
		
		// Calendar navigation functions
		const nextMonths = () => {
			calendar.value.currentMonth += 3;
			// If we've gone past December, increment the year
			if (calendar.value.currentMonth >= 12) {
				calendar.value.currentMonth -= 12;
				calendar.value.currentYear += 1;
			}
			calendar.value.displayedMonths = generateDisplayedMonths(calendar.value.currentYear, calendar.value.currentMonth);
		};
		
		const previousMonths = () => {
			calendar.value.currentMonth -= 3;
			// If we've gone before January, decrement the year
			if (calendar.value.currentMonth < 0) {
				calendar.value.currentMonth += 12;
				calendar.value.currentYear -= 1;
			}
			// Don't allow going before current year
			if (calendar.value.currentYear < today.getFullYear()) {
				calendar.value.currentYear = today.getFullYear();
				calendar.value.currentMonth = today.getMonth();
			}
			calendar.value.displayedMonths = generateDisplayedMonths(calendar.value.currentYear, calendar.value.currentMonth);
		};
		
		const nextYear = () => {
			calendar.value.currentYear += 1;
			calendar.value.displayedMonths = generateDisplayedMonths(calendar.value.currentYear, calendar.value.currentMonth);
		};
		
		const previousYear = () => {
			if (calendar.value.currentYear > today.getFullYear()) {
				calendar.value.currentYear -= 1;
				calendar.value.displayedMonths = generateDisplayedMonths(calendar.value.currentYear, calendar.value.currentMonth);
			}
		};

		// Initialize the calendar


		
	
		
		// Calculate price based on form data
		const calculatedPrice = computed(() => {
			const squareFeet = formData.value.squareFeet || 0;
			const serviceRate = pricing[formData.value.serviceType] || 0;
			let basePrice = squareFeet * serviceRate;
			
			let discount = 0;
			if (formData.value.frequency === 'weekly') {
				discount = basePrice * pricing.discounts.weekly;
			} else if (formData.value.frequency === 'monthly') {
				discount = basePrice * pricing.discounts.monthly;
			}
			
			const totalPrice = basePrice - discount;
			
			return {
				basePrice: basePrice.toFixed(2),
				discount: discount.toFixed(2),
				totalPrice: totalPrice.toFixed(2)
			};
		});
		
		// Format date for display
		const formatDate = (dateString) => {
			if (!dateString) return '';
			const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
			return new Date(dateString).toLocaleDateString('en-US', options);
		};
		
		// Get service type label
		const serviceTypeLabel = computed(() => {
			return formData.value.serviceType === 'basic' ? 'Basic Cleaning' : 
				   formData.value.serviceType === 'deep' ? 'Deep Cleaning' : 'Move In/Out Cleaning';
		});
		
		// Get frequency label
		const frequencyLabel = computed(() => {
			return formData.value.frequency === 'once' ? 'One Time' : 
				   formData.value.frequency === 'weekly' ? 'Weekly (10% OFF)' : 'Monthly (15% OFF)';
		});
		
		// Get time slot label
		const timeSlotLabel = computed(() => {
			const slot = availableTimeSlots.find(s => s.value === formData.value.timeSlot);
			return slot ? slot.label : 'Not selected';
		});
		
		// Navigate to next step
		const nextStep = () => {
			if (validateStep(step.value)) {
				if (step.value < steps.length - 1) {
					step.value++;
				} else {
					// Submit form
					alert('Booking submitted successfully!');
					console.log('Form data:', formData.value);
				}
			}
		};
		
		// Navigate to previous step
		const prevStep = () => {
			if (step.value > 0) {
				step.value--;
			}
		};
		
		// Validate current step
		const validateStep = (step) => {
			let isValid = true;
			
			// Reset errors
			errors.value = {
				fullName: '',
				email: '',
				phone: '',
				location: '',
				address: '',
				paymentMethod: ''
			};

			switch(step) {
				case 0: // Location step
					if (!formData.value.location) {
						errors.value.location = 'Please select your location';
						isValid = false;
					}
					if (!formData.value.address) {
						errors.value.address = 'Address is required';
						isValid = false;
					}
					break;
				case 1: // Service step
					if (!formData.value.squareFeet || formData.value.squareFeet < 50) {
						alert('Please enter a valid property size (minimum 50 ft²)');
						isValid = false;
					}
					break;
				case 2: // Schedule step
					if (!formData.value.date) {
						alert('Please select a cleaning date');
						isValid = false;
					} else if (isDateBooked(formData.value.date)) {
						alert('The selected date is already booked. Please choose another date.');
						isValid = false;
					} else if (isDateOffDay(formData.value.date)) {
						alert('The selected date is an off day. Please choose another date.');
						isValid = false;
					}
					if (!formData.value.timeSlot) {
						alert('Please select a time slot');
						isValid = false;
					}
					break;
				case 3: // Your Info step
					if (!formData.value.fullName) {
						errors.value.fullName = 'Full name is required';
						isValid = false;
					}
					if (!formData.value.email) {
						errors.value.email = 'Email is required';
						isValid = false;
					} else if (!/^\S+@\S+\.\S+$/.test(formData.value.email)) {
						errors.value.email = 'Please enter a valid email';
						isValid = false;
					}
					if (!formData.value.phone) {
						errors.value.phone = 'Phone number is required';
						isValid = false;
					} else if (formData.value.phone.length < 10) {
						errors.value.phone = 'Phone number must be at least 10 digits';
						isValid = false;
					}
					if (!formData.value.paymentMethod) {
						errors.value.paymentMethod = 'Please select a payment method';
						isValid = false;
					}
					break;
			}
			
			return isValid;
		};
		const isbtnDisabled=ref(false);
		const handleConfirmBooking = () => {
			// If on last step, confirm booking
			if (step.value === steps.length - 1) {

			  confirmBooking();
			 

			} else {
			  nextStep(); // Otherwise, proceed to the next step
			}
		  };

		  const confirmBooking = () => {
			isbtnDisabled.value = true;
						
			const bookingData = {
			  propertyType: formData.value.propertyType,
			  squareFeet: formData.value.squareFeet,
			  serviceType: formData.value.serviceType,
			  frequency: formData.value.frequency,
			  date: formData.value.date,
			  timeSlot: formData.value.timeSlot,
			  specialRequests: formData.value.specialRequests,
			  fullName: formData.value.fullName,
			  email: formData.value.email,
			  phone: formData.value.phone,
			  location: formData.value.location,
			  address: formData.value.address,
			  paymentMethod: formData.value.paymentMethod,
			};
			
			// Sending the AJAX request
			fetch(locationAjax.ajax_url, {
			  method: 'POST',
			  headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			  },
			  body: new URLSearchParams({
				action: 'confirm_booking',           // The action hook that processes the booking
				_ajax_nonce: locationAjax.nonce,    // Nonce to secure the request
				bookingData: JSON.stringify(bookingData), // Send the form data as a JSON string
			  })
			})
			.then(res => res.json())
			.then(data => {
			  if (data.success) {
				console.log('Booking Confirmed!', data);
				// Handle successful booking (e.g., display a success message)
				$('.booking_button').text('Booking  Confirmed!');
				
			  } else {
				console.error('Failed to confirm booking', data);
				// Handle failed booking (e.g., display an error message)
			  }
			})
			.catch(error => {
			  console.error('Error:', error);
			  // Handle network errors
			});
		  };
		  
	  
		return {
			steps,
			step,
			handleConfirmBooking,
			formData,
			errors,
			pricing,
			predefinedLocations,
			availableTimeSlots,
			today: todayStr,
			maxDate: maxDateFormatted,
			calculatedPrice,
			serviceTypeLabel,
			frequencyLabel,
			timeSlotLabel,
			calendar,
			isDateBooked,
			isbtnDisabled,
			isDateOffDay,
			isDateSelected,
			selectCalendarDate,
			formatSelectedDate,
			nextMonths,
			previousMonths,
			nextYear,
			previousYear,
			nextStep,
			prevStep,
			formatDate,
		};
	}
}).mount('#cleaningapp');



});