<div id="cleaningapp">
    <div class="sc-body">
        <div class="sc-container">
            <div class="sc-form-header">
                <h1><i class="fas fa-broom"></i> <?php echo __('HouseClean', 'cbf-house-cleaning'); ?></h1>
                <p><?php echo __('Book your professional house cleaning service in just a few steps', 'cbf-house-cleaning'); ?></p>
            </div>

            <div class="sc-progress-bar">
                <div class="sc-progress-step" 
                     :class="{ 'sc-completed': step > 0, 'sc-active': step === 0 }">
                    <div class="sc-step-number">1</div>
                    <div class="sc-step-label"><?php echo __('Post Code', 'cbf-house-cleaning'); ?></div>
                </div>
                <div class="sc-progress-step" 
                     :class="{ 'sc-completed': step > 1, 'sc-active': step === 1 }">
                    <div class="sc-step-number">2</div>
                    <div class="sc-step-label"><?php echo __('Service', 'cbf-house-cleaning'); ?></div>
                </div>
                <div class="sc-progress-step" 
                     :class="{ 'sc-completed': step > 2, 'sc-active': step === 2 }">
                    <div class="sc-step-number">3</div>
                    <div class="sc-step-label"><?php echo __('Schedule', 'cbf-house-cleaning'); ?></div>
                </div>
                <div class="sc-progress-step" 
                     :class="{ 'sc-completed': step > 3, 'sc-active': step === 3 }">
                    <div class="sc-step-number">4</div>
                    <div class="sc-step-label"><?php echo __('Your Info', 'cbf-house-cleaning'); ?></div>
                </div>
                <div class="sc-progress-step" 
                     :class="{ 'sc-completed': step > 4, 'sc-active': step === 4 }">
                    <div class="sc-step-number">5</div>
                    <div class="sc-step-label"><?php echo __('Summary', 'cbf-house-cleaning'); ?></div>
                </div>
            </div>

            <div class="sc-form-body">
                <!-- Step 1: Location -->
                <div class="sc-form-step" :class="{ 'sc-active': step === 0 }">
                    <div class="sc-form-group">
                        <label for="location" class="required"><?php echo __('Post Code', 'cbf-house-cleaning'); ?></label>
                        <select id="location" class="sc-form-control" 
                                :class="{ 'sc-invalid': errors.location }"
                                v-model="formData.location">
                            <option value=""><?php echo __('Select a Postcode', 'cbf-house-cleaning'); ?></option>
                            <option v-for="loc in predefinedLocations" :value="loc">{{ loc }}</option>
                        </select>
                        <div class="sc-error" v-if="errors.location">{{ errors.location }}</div>
                    </div>

                    <div class="sc-form-group">
                        <label for="address" class="required"><?php echo __('Full Address', 'cbf-house-cleaning'); ?></label>
                        <textarea id="address" class="sc-form-control" rows="3" 
                                  :class="{ 'sc-invalid': errors.address }"
                                  v-model="formData.address" 
                                  placeholder="<?php echo esc_attr__('Street address, apartment/unit number', 'cbf-house-cleaning'); ?>"></textarea>
                        <div class="sc-error" v-if="errors.address">{{ errors.address }}</div>
                    </div>

                    <div class="sc-form-group">
                        <label for="property-type"><?php echo __('Property Type', 'cbf-house-cleaning'); ?></label>
                        <select id="property-type" class="sc-form-control" v-model="formData.propertyType">
                            <option value=""><?php echo __('Select property type', 'cbf-house-cleaning'); ?></option>
                            <option value="house"><?php echo __('House', 'cbf-house-cleaning'); ?></option>
                            <option value="apartment"><?php echo __('Apartment', 'cbf-house-cleaning'); ?></option>
                        </select>
                    </div>

                    <div class="sc-form-group">
                        <label for="square-feet"><?php echo __('Property Size (Square Feet)', 'cbf-house-cleaning'); ?></label>
                        <div class="sc-square-feet-input">
                            <input type="number" id="square-feet" class="sc-form-control" 
                                   v-model.number="formData.squareFeet" 
                                   placeholder="<?php echo esc_attr__('Enter square footage', 'cbf-house-cleaning'); ?>" min="50" max="200">
                            <span class="sc-unit">ft²</span>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Service Details -->
                <div class="sc-form-step" :class="{ 'sc-active': step === 1 }">
                    <div class="sc-form-group">
                        <label><?php echo __('Select Cleaning Service', 'cbf-house-cleaning'); ?></label>
                        <div class="sc-service-options">
                            <div class="sc-service-option" 
                                 :class="{ 'sc-selected': formData.serviceType === 'basic' }"
                                 @click="formData.serviceType = 'basic'">
                                <h3><?php echo __('Basic Cleaning', 'cbf-house-cleaning'); ?></h3>
                                <p><?php echo __('Dusting, vacuuming, mopping, bathroom cleaning, kitchen cleaning', 'cbf-house-cleaning'); ?></p>
                                <div class="sc-price">{{pricing.basic}}kr</div>
                            </div>
                            <div class="sc-service-option" 
                                 :class="{ 'sc-selected': formData.serviceType === 'deep' }"
                                 @click="formData.serviceType = 'deep'">
                                <h3><?php echo __('Deep Cleaning', 'cbf-house-cleaning'); ?></h3>
                                <p><?php echo __('Everything in Basic plus inside appliances, inside windows, detailed scrubbing', 'cbf-house-cleaning'); ?></p>
                                <div class="sc-price">{{pricing.deep}}kr</div>
                            </div>
                            <div class="sc-service-option" 
                                 :class="{ 'sc-selected': formData.serviceType === 'move' }"
                                 @click="formData.serviceType = 'move'">
                                <div class="sc-badge"><?php echo __('Popular', 'cbf-house-cleaning'); ?></div>
                                <h3><?php echo __('Move In/Out Cleaning', 'cbf-house-cleaning'); ?></h3>
                                <p><?php echo __('Everything in Deep plus inside cabinets, walls, baseboards, and closets', 'cbf-house-cleaning'); ?></p>
                                <div class="sc-price">{{pricing.move}}kr</div>
                            </div>
                        </div>
                    </div>

                    <div class="sc-form-group">
                        <label><?php echo __('Cleaning Frequency', 'cbf-house-cleaning'); ?></label>
                        <div class="sc-radio-group">
                            <div class="sc-radio-option">
                                <input type="radio" name="frequency" id="frequency-once" 
                                       v-model="formData.frequency" value="once">
                                <label for="frequency-once"><?php echo __('One Time', 'cbf-house-cleaning'); ?></label>
                            </div>
                            <div class="sc-radio-option">
                                <input type="radio" name="frequency" id="frequency-weekly" 
                                       v-model="formData.frequency" value="weekly">
                                <label for="frequency-weekly"><?php echo __('2-Weekly', 'cbf-house-cleaning'); ?> <span class="sc-discount">{{(pricing.discounts.weekly*100).toFixed(0)}}%</span></label>
                            </div>
                            <div class="sc-radio-option">
                                <input type="radio" name="frequency" id="frequency-monthly" 
                                       v-model="formData.frequency" value="monthly">
                                <label for="frequency-monthly"><?php echo __('Monthly', 'cbf-house-cleaning'); ?> <span class="sc-discount">{{(pricing.discounts.monthly*100).toFixed(0)}}%</span></label>
                            </div>
                        </div>
                    </div>

                    <div class="sc-price-display">
                        <div class="sc-label"><?php echo __('Estimated Price:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-amount">Kr{{ calculatedPrice.totalPrice }}</div>
                    </div>
                </div>

                <!-- Step 3: Schedule -->
                <div class="sc-form-step" :class="{ 'sc-active': step === 2 }">
                    <div class="sc-form-group">
                        <label><?php echo __('Select Cleaning Date', 'cbf-house-cleaning'); ?></label>
                        <div class="sc-calendar-container">
                            <div class="sc-calendar-header">
                                <h3 class="sc-calendar-title"><?php echo __('Booking Calendar', 'cbf-house-cleaning'); ?></h3>
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
                                <!-- Month names will be handled in JavaScript -->
                                <div class="sc-month-container" v-for="month in calendar.displayedMonths" :key="month.key">
                                    <h3 class="sc-month-header">{{ month.name }} {{ calendar.currentYear }}</h3>
                                    <div class="sc-month-calendar">
                                        <!-- Day headers will be handled in JavaScript -->
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
                                    <span><?php echo __('Available', 'cbf-house-cleaning'); ?></span>
                                </div>
                                <div class="sc-legend-item">
                                    <div class="sc-legend-color" style="background-color: var(--sc-danger);"></div>
                                    <span><?php echo __('Booked', 'cbf-house-cleaning'); ?></span>
                                </div>
                                <div class="sc-legend-item">
                                    <div class="sc-legend-color" style="background-color: var(--sc-warning);"></div>
                                    <span><?php echo __('Day Off', 'cbf-house-cleaning'); ?></span>
                                </div>
                                <div class="sc-legend-item">
                                    <div class="sc-legend-color" style="background-color: var(--sc-primary);"></div>
                                    <span><?php echo __('Selected', 'cbf-house-cleaning'); ?></span>
                                </div>
                                <div class="sc-legend-item">
                                    <div class="sc-legend-color" style="border: 1px solid var(--sc-primary); background-color: white;"></div>
                                    <span><?php echo __('Today', 'cbf-house-cleaning'); ?></span>
                                </div>
                            </div>

                            <div class="sc-selected-date-info" v-if="formData.date">
                                <div class="sc-selected-date-label"><?php echo __('Selected Date:', 'cbf-house-cleaning'); ?></div>
                                <div class="sc-selected-date-value">{{ formatSelectedDate(formData.date) }}</div>
                                <div v-if="isDateBooked(formData.date)" style="color: var(--sc-danger); margin-top: 5px;">
                                    <?php echo __('This date is already booked', 'cbf-house-cleaning'); ?>
                                </div>
                                <div v-else-if="isDateOffDay(formData.date)" style="color: var(--sc-warning); margin-top: 5px;">
                                    <?php echo __('This is a day off (no bookings available)', 'cbf-house-cleaning'); ?>
                                </div>
                                <div v-else style="color: var(--sc-success); margin-top: 5px;">
                                    <?php echo __('This date is available for booking', 'cbf-house-cleaning'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sc-form-group">
                        <label><?php echo __('Available Time Slots', 'cbf-house-cleaning'); ?></label>
                        <div class="sc-radio-group">
                            <!-- Time slots will be handled in JavaScript -->
                            <div class="sc-radio-option" v-for="slot in availableTimeSlots" :key="slot.value">
                                <input type="radio" name="time-slot" :id="'time-slot-'+slot.value" 
                                       v-model="formData.timeSlot" :value="slot.value">
                                <label :for="'time-slot-'+slot.value">{{ slot.label }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="sc-form-group">
                        <label for="special-requests"><?php echo __('Special Requests (Optional)', 'cbf-house-cleaning'); ?></label>
                        <textarea id="special-requests" class="sc-form-control" rows="3" 
                                  v-model="formData.specialRequests" 
                                  placeholder="<?php echo esc_attr__('Any special instructions for our team...', 'cbf-house-cleaning'); ?>"></textarea>
                    </div>
                </div>

                <!-- Step 4: Your Info -->
                <div class="sc-form-step" :class="{ 'sc-active': step === 3 }">
                    <div class="sc-form-group">
                        <label for="full-name" class="required"><?php echo __('Full Name', 'cbf-house-cleaning'); ?></label>
                        <input type="text" id="full-name" class="sc-form-control" 
                               :class="{ 'sc-invalid': errors.fullName }"
                               v-model="formData.fullName" placeholder="<?php echo esc_attr__('Your full name', 'cbf-house-cleaning'); ?>">
                        <div class="sc-error" v-if="errors.fullName">{{ errors.fullName }}</div>
                    </div>

                    <div class="sc-form-group">
                        <label for="email" class="required"><?php echo __('Email Address', 'cbf-house-cleaning'); ?></label>
                        <input type="email" id="email" class="sc-form-control" 
                               :class="{ 'sc-invalid': errors.email }"
                               v-model="formData.email" placeholder="<?php echo esc_attr__('Your email address', 'cbf-house-cleaning'); ?>">
                        <div class="sc-error" v-if="errors.email">{{ errors.email }}</div>
                    </div>

                    <div class="sc-form-group">
                        <label for="phone" class="required"><?php echo __('Phone Number', 'cbf-house-cleaning'); ?></label>
                        <input type="tel" id="phone" class="sc-form-control" 
                               :class="{ 'sc-invalid': errors.phone }"
                               v-model="formData.phone" placeholder="<?php echo esc_attr__('Your phone number', 'cbf-house-cleaning'); ?>">
                        <div class="sc-error" v-if="errors.phone">{{ errors.phone }}</div>
                    </div>

                    <div class="sc-form-group">
                        <label for="payment-method" class="required"><?php echo __('Payment Method', 'cbf-house-cleaning'); ?></label>
                        <select id="payment-method" class="sc-form-control" 
                                :class="{ 'sc-invalid': errors.paymentMethod }"
                                v-model="formData.paymentMethod">                              
                            <option value="invoice"><?php echo __('Invoice', 'cbf-house-cleaning'); ?></option>
                        </select>
                        <div class="sc-error" v-if="errors.paymentMethod">{{ errors.paymentMethod }}</div>
                    </div>
                </div>

                <!-- Step 5: Summary -->
                <div class="sc-form-step" :class="{ 'sc-active': step === 4 }">
                    <h2 style="margin-bottom: 20px;"><?php echo __('Booking Summary', 'cbf-house-cleaning'); ?></h2>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Location:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ formData.location }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Full Address:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ formData.address }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Property Type:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ formData.propertyType || '<?php echo esc_js(__('Not specified', 'cbf-house-cleaning')); ?>' }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Service Type:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ serviceTypeLabel }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Property Size:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ formData.squareFeet }} ft²</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Frequency:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ frequencyLabel }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Scheduled Date:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ formData.date ? formatDate(formData.date) : '<?php echo esc_js(__('Not selected', 'cbf-house-cleaning')); ?>' }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Time Slot:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">{{ timeSlotLabel }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Base Price:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">Kr{{ calculatedPrice.basePrice }}</div>
                    </div>
                    
                    <div class="sc-summary-item">
                        <div class="sc-label"><?php echo __('Discount:', 'cbf-house-cleaning'); ?></div>
                        <div class="sc-value">-Kr{{
                            formData.frequency === 'once' ? '0.00' : 
                            formData.frequency === 'weekly' ? calculatedPrice.discount : 
                            calculatedPrice.discount
                        }}</div>
                    </div>
                    
                    <div class="sc-total-price">
                        <?php echo __('Total:', 'cbf-house-cleaning'); ?> <span>Kr{{ calculatedPrice.totalPrice }}</span>
                    </div>
                </div>
            </div>

            <div class="sc-form-footer">
                <button class="sc-btn sc-btn-outline" 
                        @click="prevStep" 
                        :disabled="step === 0"><?php echo __('Previous', 'cbf-house-cleaning'); ?></button>
                <button class="sc-btn booking_button sc-btn-primary" 
                @click="handleConfirmBooking"
                :disabled="isbtnDisabled">
                        {{ step === steps.length - 1 ? '<?php echo esc_js(__('Confirm Booking', 'cbf-house-cleaning')); ?>' : '<?php echo esc_js(__('Next', 'cbf-house-cleaning')); ?>' }}
                </button>
            </div>
        </div>
    </div>
</div>