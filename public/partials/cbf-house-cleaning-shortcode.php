


   
    <div id="cleaningapp">
        <div class="sc-body">
            <div class="sc-container">
                <div class="sc-form-header">
                    <h1><i class="fas fa-broom"></i> HouseClean</h1>
                    <p>Book your professional house cleaning service in just a few steps</p>
                </div>

                <div class="sc-progress-bar">
                    <div class="sc-progress-step" 
                         :class="{ 'sc-completed': step > 0, 'sc-active': step === 0 }">
                        <div class="sc-step-number">1</div>
                        <div class="sc-step-label">Location</div>
                    </div>
                    <div class="sc-progress-step" 
                         :class="{ 'sc-completed': step > 1, 'sc-active': step === 1 }">
                        <div class="sc-step-number">2</div>
                        <div class="sc-step-label">Service</div>
                    </div>
                    <div class="sc-progress-step" 
                         :class="{ 'sc-completed': step > 2, 'sc-active': step === 2 }">
                        <div class="sc-step-number">3</div>
                        <div class="sc-step-label">Schedule</div>
                    </div>
                    <div class="sc-progress-step" 
                         :class="{ 'sc-completed': step > 3, 'sc-active': step === 3 }">
                        <div class="sc-step-number">4</div>
                        <div class="sc-step-label">Your Info</div>
                    </div>
                    <div class="sc-progress-step" 
                         :class="{ 'sc-completed': step > 4, 'sc-active': step === 4 }">
                        <div class="sc-step-number">5</div>
                        <div class="sc-step-label">Summary</div>
                    </div>
                </div>

                <div class="sc-form-body">
                    <!-- Step 1: Location -->
                    <div class="sc-form-step" :class="{ 'sc-active': step === 0 }">
                        <div class="sc-form-group">
                            <label for="location" class="required">Service Location</label>
                            <select id="location" class="sc-form-control" 
                                    :class="{ 'sc-invalid': errors.location }"
                                    v-model="formData.location">
                                <option value="">Select your location</option>
                                <option v-for="loc in predefinedLocations" :value="loc">{{ loc }}</option>
                            </select>
                            <div class="sc-error" v-if="errors.location">{{ errors.location }}</div>
                        </div>

                        <div class="sc-form-group">
                            <label for="address" class="required">Full Address</label>
                            <textarea id="address" class="sc-form-control" rows="3" 
                                      :class="{ 'sc-invalid': errors.address }"
                                      v-model="formData.address" 
                                      placeholder="Street address, apartment/unit number"></textarea>
                            <div class="sc-error" v-if="errors.address">{{ errors.address }}</div>
                        </div>

                        <div class="sc-form-group">
                            <label for="property-type">Property Type</label>
                            <select id="property-type" class="sc-form-control" v-model="formData.propertyType">
                                <option value="">Select property type</option>
                                <option value="house">House</option>
                                <option value="apartment">Apartment</option>
                                <option value="condo">Condo</option>
                                <option value="office">Office</option>
                            </select>
                        </div>

                        <div class="sc-form-group">
                            <label for="square-feet">Property Size (Square Feet)</label>
                            <div class="sc-square-feet-input">
                                <input type="number" id="square-feet" class="sc-form-control" 
                                       v-model.number="formData.squareFeet" 
                                       placeholder="Enter square footage" min="50" max="10000">
                                <span class="sc-unit">ft²</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Service Details -->
                    <div class="sc-form-step" :class="{ 'sc-active': step === 1 }">
                        <div class="sc-form-group">
                            <label>Select Cleaning Service</label>
                            <div class="sc-service-options">
                                <div class="sc-service-option" 
                                     :class="{ 'sc-selected': formData.serviceType === 'basic' }"
                                     @click="formData.serviceType = 'basic'">
                                    <h3>Basic Cleaning</h3>
                                    <p>Dusting, vacuuming, mopping, bathroom cleaning, kitchen cleaning</p>
                                    <div class="sc-price">{{pricing.basic}}kr</div>
                                </div>
                                <div class="sc-service-option" 
                                     :class="{ 'sc-selected': formData.serviceType === 'deep' }"
                                     @click="formData.serviceType = 'deep'">
                                    <h3>Deep Cleaning</h3>
                                    <p>Everything in Basic plus inside appliances, inside windows, detailed scrubbing</p>
                                    <div class="sc-price">{{pricing.deep}}kr</div>
                                </div>
                                <div class="sc-service-option" 
                                     :class="{ 'sc-selected': formData.serviceType === 'move' }"
                                     @click="formData.serviceType = 'move'">
                                    <div class="sc-badge">Popular</div>
                                    <h3>Move In/Out Cleaning</h3>
                                    <p>Everything in Deep plus inside cabinets, walls, baseboards, and closets</p>
                                    <div class="sc-price">{{pricing.move}}kr</div>
                                </div>
                            </div>
                        </div>

                        <div class="sc-form-group">
                            <label>Cleaning Frequency</label>
                            <div class="sc-radio-group">
                                <div class="sc-radio-option">
                                    <input type="radio" name="frequency" id="frequency-once" 
                                           v-model="formData.frequency" value="once">
                                    <label for="frequency-once">One Time</label>
                                </div>
                                <div class="sc-radio-option">
                                    <input type="radio" name="frequency" id="frequency-weekly" 
                                           v-model="formData.frequency" value="weekly">
                                    <label for="frequency-weekly">Weekly <span class="sc-discount">{{(pricing.discounts.weekly*100).toFixed(0)}}%</span></label>
                                </div>
                                <div class="sc-radio-option">
                                    <input type="radio" name="frequency" id="frequency-monthly" 
                                           v-model="formData.frequency" value="monthly">
                                    <label for="frequency-monthly">Monthly <span class="sc-discount">{{(pricing.discounts.monthly*100).toFixed(0)}}%</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="sc-price-display">
                            <div class="sc-label">Estimated Price:</div>
                            <div class="sc-amount">Kr{{ calculatedPrice.totalPrice }}</div>
                        </div>
                    </div>

                    <!-- Step 3: Schedule -->
                    <div class="sc-form-step" :class="{ 'sc-active': step === 2 }">
                        <div class="sc-form-group">
                            <label>Select Cleaning Date</label>
                            <div class="sc-calendar-container">
                                <div class="sc-calendar-header">
                                    <h3 class="sc-calendar-title">Booking Calendar</h3>
                                    <div class="sc-calendar-nav">
                                        <button class="sc-calendar-nav-btn" @click="previousYear" :disabled="calendar.currentYear <= new Date().getFullYear()">
                                            &lt;&lt;
                                        </button>
                                        <button class="sc-calendar-nav-btn" @click="previousMonths">
                                            &lt;
                                        </button>
                                        <span class="sc-calendar-nav-btn" style="background-color: transparent; color: var(--sc-dark); cursor: default;">{{ calendar.currentYear }}</span>
                                        <button class="sc-calendar-nav-btn" @click="nextMonths">
                                            &gt;
                                        </button>
                                        <button class="sc-calendar-nav-btn" @click="nextYear">
                                            &gt;&gt;
                                        </button>
                                    </div>
                                </div>

                                <div class="sc-calendar-main">
                                    <div class="sc-month-container" v-for="month in calendar.displayedMonths" :key="month.key">
                                        <h3 class="sc-month-header">{{ month.name }} {{ calendar.currentYear }}</h3>
                                        <div class="sc-month-calendar">
                                            <div class="sc-day-header" v-for="day in calendar.dayHeaders" :key="day">{{ day }}</div>
                                            <div 
                                                class="sc-calendar-day" 
                                                v-for="day in month.days" 
                                                :key="day.date"
                                                :class="{
                                                    'sc-day-disabled': !day.isCurrentMonth,
                                                    'sc-day-today': day.isToday,
                                                    'sc-day-booked': day.isBooked,
                                                    'sc-day-off': day.isOffDay,
                                                    'sc-day-available': day.isAvailable && day.isCurrentMonth,
                                                    'sc-day-selected': isDateSelected(day.date)
                                                }"
                                                @click="selectCalendarDate(day)"
                                            >
                                                {{ day.dayNumber }}
                                                <div class="sc-day-indicator" v-if="day.isCurrentMonth && (day.isBooked || day.isOffDay || day.isAvailable)"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="sc-calendar-legend">
                                    <div class="sc-legend-item">
                                        <div class="sc-legend-color" style="background-color: rgba(108, 92, 231, 0.1);"></div>
                                        <span>Available</span>
                                    </div>
                                    <div class="sc-legend-item">
                                        <div class="sc-legend-color" style="background-color: var(--sc-danger);"></div>
                                        <span>Booked</span>
                                    </div>
                                    <div class="sc-legend-item">
                                        <div class="sc-legend-color" style="background-color: var(--sc-warning);"></div>
                                        <span>Day Off</span>
                                    </div>
                                    <div class="sc-legend-item">
                                        <div class="sc-legend-color" style="background-color: var(--sc-primary);"></div>
                                        <span>Selected</span>
                                    </div>
                                    <div class="sc-legend-item">
                                        <div class="sc-legend-color" style="border: 1px solid var(--sc-primary); background-color: white;"></div>
                                        <span>Today</span>
                                    </div>
                                </div>

                                <div class="sc-selected-date-info" v-if="formData.date">
                                    <div class="sc-selected-date-label">Selected Date:</div>
                                    <div class="sc-selected-date-value">{{ formatSelectedDate(formData.date) }}</div>
                                    <div v-if="isDateBooked(formData.date)" style="color: var(--sc-danger); margin-top: 5px;">
                                        This date is already booked
                                    </div>
                                    <div v-else-if="isDateOffDay(formData.date)" style="color: var(--sc-warning); margin-top: 5px;">
                                        This is a day off (no bookings available)
                                    </div>
                                    <div v-else style="color: var(--sc-success); margin-top: 5px;">
                                        This date is available for booking
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sc-form-group">
                            <label>Available Time Slots</label>
                            <div class="sc-radio-group">
                                <div class="sc-radio-option" v-for="slot in availableTimeSlots" :key="slot.value">
                                    <input type="radio" name="time-slot" :id="'time-slot-'+slot.value" 
                                           v-model="formData.timeSlot" :value="slot.value">
                                    <label :for="'time-slot-'+slot.value">{{ slot.label }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="sc-form-group">
                            <label for="special-requests">Special Requests (Optional)</label>
                            <textarea id="special-requests" class="sc-form-control" rows="3" 
                                      v-model="formData.specialRequests" 
                                      placeholder="Any special instructions for our team..."></textarea>
                        </div>
                    </div>

                    <!-- Step 4: Your Info -->
                    <div class="sc-form-step" :class="{ 'sc-active': step === 3 }">
                        <div class="sc-form-group">
                            <label for="full-name" class="required">Full Name</label>
                            <input type="text" id="full-name" class="sc-form-control" 
                                   :class="{ 'sc-invalid': errors.fullName }"
                                   v-model="formData.fullName" placeholder="Your full name">
                            <div class="sc-error" v-if="errors.fullName">{{ errors.fullName }}</div>
                        </div>

                        <div class="sc-form-group">
                            <label for="email" class="required">Email Address</label>
                            <input type="email" id="email" class="sc-form-control" 
                                   :class="{ 'sc-invalid': errors.email }"
                                   v-model="formData.email" placeholder="Your email address">
                            <div class="sc-error" v-if="errors.email">{{ errors.email }}</div>
                        </div>

                        <div class="sc-form-group">
                            <label for="phone" class="required">Phone Number</label>
                            <input type="tel" id="phone" class="sc-form-control" 
                                   :class="{ 'sc-invalid': errors.phone }"
                                   v-model="formData.phone" placeholder="Your phone number">
                            <div class="sc-error" v-if="errors.phone">{{ errors.phone }}</div>
                        </div>

                        <div class="sc-form-group">
                            <label for="payment-method" class="required">Payment Method</label>
                            <select id="payment-method" class="sc-form-control" 
                                    :class="{ 'sc-invalid': errors.paymentMethod }"
                                    v-model="formData.paymentMethod">
                                <option value="">Select payment method</option>
                                <option value="credit">Credit/Debit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="cash">Cash (Pay on arrival)</option>
                            </select>
                            <div class="sc-error" v-if="errors.paymentMethod">{{ errors.paymentMethod }}</div>
                        </div>
                    </div>

                    <!-- Step 5: Summary -->
                    <div class="sc-form-step" :class="{ 'sc-active': step === 4 }">
                        <h2 style="margin-bottom: 20px;">Booking Summary</h2>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Location:</div>
                            <div class="sc-value">{{ formData.location }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Full Address:</div>
                            <div class="sc-value">{{ formData.address }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Property Type:</div>
                            <div class="sc-value">{{ formData.propertyType || 'Not specified' }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Service Type:</div>
                            <div class="sc-value">{{ serviceTypeLabel }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Property Size:</div>
                            <div class="sc-value">{{ formData.squareFeet }} ft²</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Frequency:</div>
                            <div class="sc-value">{{ frequencyLabel }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Scheduled Date:</div>
                            <div class="sc-value">{{ formData.date ? formatDate(formData.date) : 'Not selected' }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Time Slot:</div>
                            <div class="sc-value">{{ timeSlotLabel }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Base Price:</div>
                            <div class="sc-value">Kr{{ calculatedPrice.basePrice }}</div>
                        </div>
                        
                        <div class="sc-summary-item">
                            <div class="sc-label">Discount:</div>
                            <div class="sc-value">-Kr{{
                                formData.frequency === 'once' ? '0.00' : 
                                formData.frequency === 'weekly' ? calculatedPrice.discount : 
                                calculatedPrice.discount
                            }}</div>
                        </div>
                        
                        <div class="sc-total-price">
                            Total: <span>Kr{{ calculatedPrice.totalPrice }}</span>
                        </div>
                    </div>
                </div>

                <div class="sc-form-footer">
                    <button class="sc-btn sc-btn-outline" 
                            @click="prevStep" 
                            :disabled="step === 0">Previous</button>
                    <button class="sc-btn sc-btn-primary" 
                    @click="handleConfirmBooking">
                            {{ step === steps.length - 1 ? 'Confirm Booking' : 'Next' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

 