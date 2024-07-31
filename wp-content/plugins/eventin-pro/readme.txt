=== WP Eventin Pro ===
Contributors: themewinter
Tags: events, venue, registration, schedule, bookings, tickets, meeting, seminar, conference
Requires at least: 5.2
Tested up to: 6.5.5
Stable tag: 4.0.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html



== Changelog ==

= 4.0.3 ( July 04, 2024 )=
Fix : Google meet redirect url was wrong

= 4.0.2 ( July 01, 2024 )=
Fix : Show warning on attendee list page if extra fields are blank
Fix : Couldn't update eventiwise extra field from admin dashboard
Fix : RSVP limit couldn't work properly

= 4.0.1 ( June 27, 2024 )=
Fix : Couldn't show banner image for template two and template three

= 4.0.0 ( June 25, 2024 )=
New    : Google location auto complete supports.
New    : RSVP UI/UX updated.
Fix    : Couldn't add price on Stripe payment if attendee registration disabled.
Fix    : Warning on sales report.
Fix    : After refund attendee status should be failed.
Tweak  : Updated Google Meet platform.
Tweak  : Updated RSVP preference.
Tweak  : Optimized code blocks.
Teak   : Updated recurring events.
Teak   : Updated seat map.

= 3.3.47 ( May 20, 2024 )=
Fix   : After refund attendee status should be failed
Fix   : Ticket couldn't sync when refund a ticket
Fix   : WooCommerce purchase failed attendee issue fixed
Tweak : Event total sold tickets couldn't synced

= 3.3.46 ( May 09, 2024 )=
Fix: Couldn't make a payment using stripe

= 3.3.45 ( May 06, 2024 )=
Fix: Header already sent issue on BuddyBoss
Fix: One vendor can add other's schedule on the frontend submission
Fix: FAQ is vanishing from frontend submission after edit
Fix: Dokan dashboard issue speaker edit
Fix: Translation issue on dokan frontend
Fix: Related event's date issue on event single page

= 3.3.44 ( February 27, 2024 )=
Fix     : WC deposit not working with eventin
Fix     : Event ticket scanner breaking Unicode characters issue fixed. 
Tweak   : Event extra field checkbox link tag support added.

= 3.3.43 ( February 08, 2024 )=
Fix   : FluentCRM contact not creating issue fixed
Fix   : Safari browser certificate generation issue fixed
Fix   : Removed maxLength input limit for FAQ content
Fix   : FAQ content context updated after event has been created.
Tweak : All the calendar widgets and shortcodes are optimized for better design and performance.

= 3.3.42 ( February 05, 2024 )=
Fix : Nonce verification issue.

= 3.3.41 ( January 24, 2024 )=
Tweak   : Attendee extra field show in dashboard removed for single event.
Fix     : Invalid access token issue fixed for Zoom Meeting shortcode.

= 3.3.40 ( January 10, 2024 )=
Fix: RSVP number of attendees count issue fixed.
Fix: RSVP form option not saving issue fixed.
Fix: Eventin frontend dashboard translation issues fixed.

= 3.3.39 ( January 02, 2024 )=
New : Stripe Order import in CSV.
New : Stripe Order import in JSON.
New : Eventin frontend dashboard default timezone support added.
Fix : Google meet blank link in email issue fixed.
Fix : Eventin certificate sending faild issue fixed.
Fix : Multiple Zoom/Google Meet event checkout but one join link send issue fixed.

= 3.3.38 ( December 12, 2023 ) =
New   : Stripe Order export in CSV Format
New   : Stripe Order export in JSON Format
Tweak : UX improvement for recurring events in frontend dashboard.
Fix   : Title should be used in the repeater title.
Fix   : Event calendar blocks weren't showing anything.	
Fix   : Simple bug fix for limited tickets data on multiple ticket variation.

= 3.3.37 ( November 28, 2023 )=
Added     : Added webhook for attendee, order, schedule
Added     : Added refund on stripe payment
Added     : Added event wise custom fields
Added     : Recurring event support in the Eventin frontend dashboard
Added     : Event schedule create/read/update/delete support in the Eventin frontend dashboard
Added     : Google Meet support in the Eventin frontend dashboard
Added     : Event FAQ add support added in the Eventin frontend dashboard
Added     : Registration deadline support in the Eventin frontend dashboard
Added     : Event external link support in the Eventin frontend dashboard
Added     : Schedule type selection support in the Eventin frontend dashboard
Tweak     : Google meet support for event api
Tweak     : Get certificate template pages on api
Tweak     : Google meet addon settings added on api
Tweak     : Added FAQ on event api
Tweak     : Added recurring support to event api
Tweak     : Overall performance improvement of the Eventin frontend dashboard 
Fix       : After refunding order total revenue couldn't decrease
Fix       : Float value price for event order
Fix       : Custom field warning issue for certain event
Fix       : Manual attendee for stripe payment not ticket not available 
Fix       : The speaker delete issue was fixed in the Eventin frontend dashboard
Fix       : Non event product improvement
Fix       : Woocommerce latest version compatibility with eventin 

= 3.3.36 ( November 14, 2023 )=
Fix     : Manual attendee report not adding in purchase report
Fix     : Manual attendee purchase mail not sending
Fix     : Seatmap ticket variation name issue
Fix     : Seatmap ticket minimum and maximum quantity validation check
Fix     : Attendee list isn’t showing in Dokan dashboard
Fix     : Maximum QTY not working on seat mapping
Fix     : After refund any tickets the seat map's seat is not available again

= 3.3.35 ( Oct 25, 2023 )=
Fix     : Gutenberg header footer compatibility

= 3.3.34 ( Oct 22, 2023 )=
Added   : Gutenberg header footer compatibility
Fix     : Timezone issue in date-time details in cart, checkout & invoice 


= 3.3.33 ( Oct 10, 2023 )=
Tweak : Improved PHP 8 support
Fix   : Filter sanitize issue resolved

= 3.3.32 ( Sept 18, 2023 )=
Tweak : Admin Settings UX update 
Tweak : Event Meta Settings UX update 
Tweak : Speaker Meta Settings Update
Tweak : Schedule Meta Settings Update 

= 3.3.31 ( Aug 30, 2023 )=
Tweak: The Stripe payment method has been updated with 3D Secure support.
Fix: The issue with the event expiry time not working has been resolved.
Fix: The issue with Ticket Template 2 not showing the "Seat" properties has been fixed.
Fix: The issue with the extra input field in the seat map has been resolved.
Fix: After adding a new attendee from a manual attendee, the deprecation issues have been fixed.
Fix: The issue with single event social link icons showing doubtfully has been resolved.
Fix: The issue with the event certificate not downloading on the Safari browser has been fixed.
Fix: The issue where one vendor can scan another vendor's ticket has been fixed.

= 3.3.30 ( Aug 16, 2023 )=
* Added : Import Facebook events and show using shortcode
* Fix   : Attendee information edit issue fixed.
* Fix   : The location list pro widget issue is fixed.
* Fix   : Min Qty not working correctly fixed.
* Fix   : Attendee search filter issue fixed. 

= 3.3.29 ( July 20, 2023 )=
* Added : Media support enabled for the purchase email body. 
* Tweak : Event certificate sends attendee-wise. 
* Fix   : BuddyBoss Events feed issue fixed
* Fix   : WP Media script loading optimized

= 3.3.28 ( July 02, 2023 )=
Fix : Bulk attendee registration issue fixed
Fix : Attendee registration custom field "required fields" not working issue fixed.
Fix : Virtual event checkbox and Ticket variation button are not showing in the Frontend issue fixed. 
Fix : Non-Latin language to PDF ticket generation issue fixed.
Fix : Typo fix in the RSVP form

= 3.3.27 ( June 06, 2023 )=
* Fix: Event revenue count fix

= 3.3.26 ( May 31, 2023 )=
* Tweak: Event name and attendee name added in ticket scanner 
* Tweak: Virtual product option added in the frontend 
* Fix: Countdown expired text localized
* Fix: Frontend submission AM/PM switching automatically

= 3.3.25 ( May 07, 2023 )=
* Fix: Dokan vendor store issue with Event
* Fix: WP Cafe PRO conflict with Eventin's calendar
* Fix: Attendee list and search are not working

= 3.3.24 ( April 20, 2023 )=
* Fix: Email remainder issue
* Fix: Frontend submission issue 

= 3.3.23 ( April 12, 2023 )=
* Tweak: Bulk attendee added in the attendee submit
* Fix: Fixed stripe currency calculation issue
* Fix: Typo issue fixed

= 3.3.22 ( April 03, 2023 )=
* Add: Bulk Attendee add option in Attendee form
* Add: External link add option in Event single page
* Tweak: RSVP stock update on response change
* Tweak: RSVP Reject reason in Not Going response

= 3.3.21 ( March 15, 2023 )=
* New: Google Meet Integration
* Tweak: RSVP not going option updated for submission in a single step.
* Tweak: Add to calendar widget disabled for the expired event. 
* Fix: Frontend tag and category not saving issue fixed. 
* Fix: Certificate layout changing in mobile view issue fixed.
* Fix: Attendee filter based on payment and ticket status issue fixed.
* Fix: Google map callback issue in the console issue fixed.
* Fix: Event certificate date format not changing issue fixed.
* Fix: RSVP invalid argument in the child event issue fixed.
* Fix: Existing location not showing in the related event issue fixed.
* Fix: Event expired status not updating for same day event issue fixed.

= 3.3.20 ( February 26, 2023 )=
* Added: "Add to Calendar" widget
* Tweak: RSVP Translation fix
* Fix: Elementor widget register deprecated issue
* Fix: Eelated events order update
* Fix: Certificate sent two times
* Fix: Stripe payment declined ticket email functionality
* Fix: Countdown timer timezone Sync
* Fix: RSVP invitation Email issue
* Fix: Event trash issue

= 3.3.19 ( February 13, 2023 )=
* Fix   : Event Banner not updating

= 3.3.17 ( February 09, 2023 )=
* Added   : RSVP response , report, invitation added for event
* Fix     : Event location not updating based on location type

= 3.3.16 ( January 24, 2023 )=
* Fix     : BuddyBoss Platform PRO compatible issue fixed.
* Fix     : Calendar PRO is unable to show the "Existing Location" fixed. 
* Fix     : Event ticket form not working when WooCommerce disable fixed.
* Fix     : Event calendar list monthly title escaping special character issue fixed.
* Fix     : Map issue, "view on map" is not working correctly fixed.
* Fix     : Automatic schedule was added to an Event issue fixed. 
* Fix     : Upcoming event time calculation issue in the dashboard  fixed.
* Fix     : Location not updating based on location type in event slider issue fixed.

= 3.3.15 ( January 9, 2023 )=
* New     : Event schema markup and rich results support
* Fix     : Accessibility Issues fixed
* Fix     : Event calendar title special character issue fixed.
* Fix     : Ticket edit template design improved.
* Fix     : Multi vendor Translation Issue Fixed 
* Fix     : Frontend Builder Translation Issue Fixed 
* Fix     : BuddyBoss Assigned Group Issue Fixed 
* Fix     : Monthly Calender view style Issue Fixed

= 3.3.14 ( December 20, 2022 )=
* New     : Add Manual attendee from admin
* New     : Create event category and tag from event creating form
* New     : Bulk delete option added for front-end event dashboard 
* Tweak   : Event creating form: Image selection box reduced from six to three
* Tweak   : Event assign to group button displayed just on BuddyBoss Profile page

= 3.3.13 ( December 07, 2022 )=
* Fix    : Expire event end date issue
* Fix    : Attendee details end time issue 

= 3.3.12 ( November 30, 2022 )=
* Add    : Events list shortcode & Widget
* Add    : Ticket Email body customization option
* Tweak  : Placeholders in the Remainder email setting field
* Fix    : Bricks builder font-awesome icon conflict

= 3.3.11 ( November 23, 2022 )=
* New    :  Attendee ticket template added

= 3.3.10 ( November 16, 2022 )=
* New    :  Single event details in the right side of the BuddyBoss content.
* New    :  Create/edit/delete locations from the front-end.
* New    :  Location taxonomy with Google map integration
* New    :  Assign specific event to any group
* New    :  Show events feeds in the main BuddyBoss news feed page


= 3.3.9 ( November 8, 2022 )=
* New    : Added stripe logo option in settings
* Fix    : translation issues
* Fix    : front-end event form load issue
* Fix    : Speaker/Schedule not store issue while creating event
* Fix    : Missing Organizer field in create event form
* Fix    : Create speaker without selecting category

= 3.3.8 ( October 27, 2022 )=
* Added  : Speaker profile photo
* Fix    : Missing top navigation on attendee list page and Ticket scanner button in event list
* Tweak  : Font icon optimize
* Tweak  : Plugin file size reduce 
* Tweak  : Modular base JS, CSS load

= 3.3.7 ( October 17, 2022 )=
* New   : BuddyBoss integration
* New   : Event Embedable Scripts for single event and shortcodes
* New   : Webhook for single event and shortcodes
* New   : Auto play option in event slider PRO
* Fix   : Attendee Extra field required fields were not saved
* Fix   : Missing translation issues
* Fix   : Ant design global style conflict issue fixed
* New   : Add speakers and manage in frontend dashboard
* New   : Add speakers and manage in dokan multivendor dashboard

= 3.3.6 ( September 29, 2022 )=
* New   : Event certificate builder added

= 3.3.5 ( September 22, 2022 )=
* New   : Frontend submission (1st Phase)
* New   : Attendee list and manage attendees in Dokan multivendor
* New   : Attendee ticket scanner in Dokan multivendor
* Tweak : Decline payment status added in Strip Sells engine 
* Tweak : Verify event wise attendee ticket.

= 3.3.4 ( September 14, 2022 )=
* Added  : Child events options in shortcodes and Elementor widgets.

= 3.3.3 ( September 2, 2022 )=
* Added  : Child events options in shortcodes and Elementor widgets.
* Tweak  : Currency symbol for stripe payment
* Tweak  : End date show/hide option added on all shortcode and widgets

= 3.3.2 ( August 23, 2022 )=
* Fix   : Currency symbol for stripe payment

= 3.3.1 ( August 23, 2022 )=
* Added : Independent speaker block in the single event
* Tweak : Replaced thumbnail image with WP default thumbnail function for better performance
* Fix   : Multi vendor event redirect for edit and delete
* Fix   : Added condition for google map library loads
* Fix   : Registration deadline added for stripe sells engine

= 3.3.0 ( August 14, 2022 )=
* New : Multivendor Marketplace Feature added with Dokan
* Fix : Attendee extra field settings design issue fixed

= 3.2.2 ( July 26, 2022 )=
* Tweak : Full name column added inside CSV purchase report
* Fix   : Settings style fix
* Fix   : Event registration deadline issue fix

= 3.2.1 ( July 20, 2022 )=
* New   : Recurring events stripe payment gateway support
* New   : Stripe order invoice email
* Tweak : JS code Refactor/Optimized.
* Fix   : Feeds error handled
* Fix   : Stripe fields validation issue fixed
* Fix   : Stock update issue for attendee registration off handled
* Fix   : Resend email issue for stripe order fixed
* Fix   : Support link updated on the license page.

= 3.2.0 ( July 06, 2022 )=
* New   : Stripe payment gateway for event ticket purchase
* Tweak : Attendee support for FluentCRM
* Fix   : License documentation broken link 
* Fix   : WPML compatibility fix
* Fix   : Event location map not showing
* Fix   : Settings design fix

= 3.2.0-beta-01 ( July 04, 2022 )=
* New   : Stripe payment integration
* Tweak : CSS optimized
* Fix   : Woocommerce error issue fixed

= 3.1.5 ( June 16, 2022 )=
* New   : Search event by location shortcode, widget
* New   : Show event location in google map
* New   : QR code segment
* New   : Attendee extra field - Checkbox input
* New   : Event with calendar list view widget in Elementor
* Tweak : Style for Settings and CPT's
* Fix   : Ticket scanner undefine issue fixed


= 3.1.4 ( June 08, 2022 )=
* New   : Event Calendar Monthly List view
* New   : Event Calendar Weekly List view
* New   : Event Calendar Daily List view
* Tweak : Price column in event details report and csv
* Tweak : Design & Responsiveness of QR scanner view and popup response layout
* Fix   : Chrome browser end-time saving issue
* Fix   : Repeater title issue in CPT
* Fix   : Event banner meta conditional issue fixed

= 3.1.3 ( May 31, 2022 )=
* New    : New attendee ticket module
* New    : Actionable unique QR code for attendee management
* New    : Grounghogg CRM integration
* Fix    : General bug fixes

= 3.1.2 ( May 24, 2022 )=
* New    : Event monthly calendar view
* New    : Event yearly calendar view
* New    : Event daily calendar view
* Tweak  : Dashboard design update

= 3.1.1 ( May 22, 2022 )=
* Tweak  : Calendar module
* Fix    : Event metabox warning issue
* Fix    : Speaker metabox warning issue


= 3.1.0 ( May 12, 2022 )=
* Fix    : Sanitization and escaping fix
* Fix    : Event banner issue fix

= 3.0.6 ( May 10, 2022 )=
* Fix    : Text domain fix
* Fix    : Event registration deadline bug

= 3.0.5 ( April 28, 2022 )=
* New    : Fluent CRM integration
* Tweak  : Code refactor and performance optimization
* Fix    : Text domain issue
* Fix    : Event tab widget order by start/end  date

= 3.0.3 ( March 25, 2022 )=
* Fix    : Elementor deprecated issue fixfix
* Fix    : Recurring event ticket widget fix 

= 3.0.2 ( March 16, 2022 )=
* Tweak   : Change the container div name for ticket price increment issue
* Tweak   : Polylang conflict with WPML function 

= 3.0.1 ( March 03, 2022 )=
* Tweak  : Min-max value validation update for event ticket  
* Fix    : Event Purchase Report page warning issue
* Fix    : Recurring event Elementor widget bug while duplicating widget
* Fix    : Error on log if Eventin-free is deactivated before pro 

= 3.0.0 ( February 23, 2022 )=
* New    : Multiple / Variable ticketing system for event
* Tweak  : Recurring event elementor widget template modify
* Tweak  : Settings added to enable / disable Print and Download option on Thank-you page
* Fix    : Recurring event elementor widget not loading issue fix
* Fix    : Related events showing past events
* Fix    : WPML compatibility fix
* Fix    : Shortcode generator label and details typo issue fix
* Fix    : Event sales report - total sold quantity issue fix

= 2.6.2 ( January 27, 2022 ) =
* Fix    : Settings CSS fix
* Fix    : General settings tab flickering issue fix

= 2.6.1 ( January 19, 2022 ) =
* Tweak  : Refunded and failed price count adjusted in purchase report 
* Tweak  : WooCommerce Deposits extension compatibility
* New    : Radio input type support for attendee extra field
* New    : WPML compatibility
* New    : Zapier & Pabbly compatibility

= 2.6.0 ( December 23, 2021 ) =
* Fix    : Meta title fix
* Fix    : Label typo fix
* New    : Event min-max qty purchase feature
* New    : Event calendar module

= 2.5.6 ( December 09, 2021 ) =
* Fix    : Admin design issue fix
* Tweak  : Compatible and tested with more payment processors
* Tweak  : Event purchase architecture update
* Tweak  : Order transaction architecture update
* Tweak  : Attendee ticket architecture update

= 2.5.5 ( October 18, 2021 ) =
* Fix    : Woocommerce order data not found issue fix
* Fix    : Event reporting bug fix

= 2.5.4 ( October 04, 2021 ) =
* Fix    : License module update
* New    : Custom capability added for all CPT's

= 2.5.3 ( September 26, 2021 ) =
* Fix    : Elementor widget design issue fix

= 2.5.2 ( September 23, 2021 ) =
* Fix    : General bug fix
* Fix    : CSV bug fix
* Tweak  : Performance optimization

= 2.5.1 ( August 24, 2021 ) =
* Fix    : General bug fix
* Tweak  : Performance optimization
* New    : Shortcode for recurring events ticket form
* New    : Elementor widget for recurring events ticket form

= 2.5.0 ( August 15, 2021 ) =
* Fix    : Attendee CSV report date format issue fix 
* Tweak  : Event order sorting options updated
* Tweak  : Purchase report pagination added
* Tweak  : Attendee field structure re-designed and optimization
* New    : New input type support for attendee extra field
* New    : Recurring events

= 2.4.7 ( July 18, 2021 ) =
* Fix    : Version conflict fix
* Fix    : time format notice error fix
* Fix    : Speaker details page schedule issue fix
* Tweak  : Code optimization
* Tweak  : Show attendee extra field data on admin dashboard
* New    : Date-picker input support for attendee extra field 

= 2.4.6 ( July 06, 2021 ) =
* Fix    : Title link issue in speakers widget
* Fix    : Purchase report icon issue fix
* Fix    : Purchase report CSV export bug fix 
* Fix    : Purchase report event filtering update 
* Tweak  : Purchase report user experience update
* New    : New templates for speaker widgets
* New    : Resend event related emails from purchase report dashboard 

= 2.4.5 ( June 23, 2021 ) =
* Fix    : Validation fix
* Fix    : Events with same title conflicting while updating ticket stock
* Fix    : Events purchase report count issue fix
* Fix    : Shortcode generate issue fix
* Tweak  : Compatible with Polylang translation plugin
* Tweak  : Show event timezone on attendee ticket and Woocommerce invoice email
* New    : Timezone support added for events
* New    : Generate unique ID for attendee in attendee ticket, report and so on.

= 2.4.4 ( May 24, 2021 ) =
* Fix    : Target blank issue fix
* Fix    : Advanced search issue fixSchedule widget date separator issue fix
* Tweak  : Constants replaced with functions for increasing memory efficiency

= 2.4.3 ( May 09, 2021 ) =
* Fix    : Schedule list time issue fix on event single page
* Fix    : Schedule list time issue fix on speaker single page
* Fix    : Schedule list time issue fix on schedule widgets
* New    : Schedule style added for both list and tab

= 2.4.2 ( May 05, 2021 ) =
* Fix    : Speaker profile page schedule time issue fix
* New    : Notification settings panel added
* New    : Notification settings added for sending event details
* New    : Send event details in Woocommerce invoice e-mail
* New    : Send event details in Zoom event e-mail
* New    : Search filter added to event archive page

= 2.4.1 ( April 29, 2021 ) =
* Fix    : Fix attendee post count on dashboard dropdown
* Fix    : Ajax action updated for license module
* Tweak  : Auto play option added for slider widgets
* Tweak  : Upcoming and expired option added for event widgets
* New    : New widget for event FAQ
* New    : New event widget with tabs


= 2.4.0 ( April 13, 2021 ) =
* Fix    : Export attendee data in condition to settings 
* Fix    : License module updated for multi-site 
* Tweak  : Performance optimization

= 2.3.9 ( March 18, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : JS script fix
* Fix    : Attendee extra field markup issue fix

= 2.3.8 ( March 09, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : JS script fix
* Fix    : Speaker single page notice error fix
* Fix    : Attendee extra field translation issue fix
* Fix    : Multiple shortcode rendering issue fix
* Fix    : Spelling issue fix
* Tweak  : Attendee extra field data are now available on CSV report
* Tweak  : Render HTML tags inside speaker summary

= 2.3.7 ( February 24, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : JS script fix
* Fix    : Settings validation fix
* Tweak  : Modified attendee form fields mechanism
* New    : Add more fields to attendee form from attendee settings

= 2.3.6 ( February 18, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : JS script fix
* Tweak  : License module update
* New    : Deactivate / Revoke license from admin dashboard
* New    : More hooks added for overriding speaker and event template
* New    : Speaker widgets re-ordering feature added
* New    : Organizers widgets re-ordering feature added

= 2.3.5 ( February 14, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : Gutenberg block bug fixes
* Fix    : License scripting issue fix

= 2.3.4 ( February 11, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : Date translation issue fix
* Fix    : Override templates from child theme
* Tweak  : Code optimization and performance update
* Tweak  : More hooks added for template overriding
* Tweak  : Speaker ordering added in Speakers shortcode
* Tweak  : Template rendering structure update
* New    : More filtering options added for Attendee

= 2.3.3 ( February 02, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : Zoom meeting meta bug fix
* Tweak  : Code optimization and performance update
* Tweak  : Post meta saving architecture upgrade
* Tweak  : Enqueue scripts and styles more efficiently
* New    : New Event meta to register event as WooCommerce Virtual Product
* New    : Control / Modify / Update attendee data from admin panel
* New    : Handle attendee ticket status from admin panel
* New    : Update purchase report status automatically when admin updates WooCommerce order status
* New    : Filter attendees with Event Name
* New    : Bulk Action - change attendee status from admin dashboard
* New    : Bulk Action - download attendee details from admin dashboard

= 2.3.2 ( January 27, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Fix    : Event single page form attendee count issue
* Fix    : Translation issue
* Tweak  : Code optimization and performance update
* New    : Template structure update to make all overridable templates developer friendly

= 2.3.1 ( January 07, 2021 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Tweak  : Code optimization and performance update
* Tweak  : Dynamic related events title in Elementor Widget

= 2.3.0 ( December 29, 2020 ) =
* Fix    : General bug fix
* Fix    : CSS fix
* Tweak  : Settings module and structure change
* New    : Related Events Elementor widget
* New    : Related Events niche shortcode
* New    : Compitablity with WooCommerce Deposit extension
* New    : Attendee List shortcode and Widget added. 

= 2.2.1 ( December 15, 2020 ) =
* Fix    : CSS fix
* Fix    : General bug fix
* Fix    : Event settings update
* Fix    : Translation fix
* Tweak  : License Activation Improved  
* New    : Shortcode settings added for event ticket form
* New    : Single page customization support for child-theme

= 2.2.0 ( December 10, 2020 ) =
* Fix    : Event settings update
* Fix    : jQuery fix
* Fix    : CSS fix
* Tweak  : Notice module update
* New    : License Activation added
* New    : Separate event ticket form module 
* New    : Event ticket form widget 
* New    : Event ticket form Shortcode

= 2.1.1 ( December 3, 2020 ) =
* Tweak  : Zoom ID generate mechanism
* Tweak  : Schedule List Objective rendering update
* Tweak  : Event template rendering optimization
* Fix  	 : Slider nav color change issue fix
* Fix  	 : Performance update
* Fix    : Admin menu and banner visibility fix
* Fix    : Go pro menu bug fix
* Fix    : Countdown timer js conflict fix
* New    : Menu for direct support
* New    : Countdown timer translation added

= 2.1.0 ( November 23, 2020 ) =
* Fix  	 : Performance update
* Tweak  : Taxonomy archive page linking from event single page
* Tweak  : Event form template update

= 2.0.1 ( November 12, 2020 ) =
* Fix  	 : Query fix for speaker and schedule post
* Fix  	 : Attendee post type not found issue
* Tweak  : Woocommerce single page notice conflict with Astra theme
* New    : Show total attendee of an event inside event single page
* New    : Option to show / hide event attendee count

= 2.0.0 ( November 05, 2020 ) =
* Tweak  :  Optimization and performance update
* Fix    :  CSS fix
* Fix    :  Event single page background color / banner checking added
* Fix    :  Event dashboard title fix
* New    :  Event single page update due to the new event ticketing system
* New    :  Email settings update, send email from specific email address

= 1.0.4 =
* Tweak  :  Optimization and performance update
* New    :  More niche shortcodes
* New    :  Converted all elementor widgets into niche shortcode

= 1.0.3 =
* Fix  	 : Translation issues
* Fix  	 : Some CSS issues 
* Fix  	 : Speaker template markup


= 1.0.2 =
* Fix  	 : Translation issues fixes
* Fix  	 : Email remainder settings bug fixes
* Fix  	 : Event single page organizer-list bug fix
* Update : .pot file updated 
* New    : Make event tickets unlimited 
* New    : Restrict add-to-cart for invalid ticket quantity


= 1.0.1 =
* Fix  	 :  Design issue fixes
* Fix  	 :  Spelling fixes
* Tweak  :  Optimization and performance update
* Tweak  :  Notice user for zoom events on event single page
* Added  :  RTL layout support
* Added  :  Create event as zoom event
* Added  :  Customer will get zoom meeting joining URL inside invoice email if he/she buys zoom meeting event 
* Added  :  Print and Download invoice after successful checkout


= 1.0.0 =
* initial release



== Upgrade Notice ==


== Installation ==


1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. All Settings will be found in Admin sidebar -> Eventin 


eg.  This plugin requires Woocommerce to use all functionality.


== Description ==


We tried to give our users the best experience and speedy loading speed. We optimize our data structure and code base to do that. It leads us to use version 3.3.19 not the version 3.3.18.