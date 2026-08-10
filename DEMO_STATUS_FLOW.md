# Demo Status Flow - Harita Music Academy

## How Demo Status Works

### Tables Involved:
1. **`payments`** - Stores sales leads/inquiries
2. **`demo_bookings`** - Stores actual demo class bookings

### Status Flow:

```
┌─────────────────┐
│  Lead Created   │ Status: "Inquiry" (pending)
│  (payments)     │ Badge: Yellow/Warning
└────────┬────────┘
         │
         │ Admin clicks "📅 Book Demo"
         ▼
┌─────────────────┐
│ Demo Scheduled  │ DemoBooking created
│ (demo_bookings) │ Status: "Demo Scheduled" (scheduled)
└────────┬────────┘ Badge: Blue/Info
         │
         │ Demo class happens → Admin updates in Demos page
         ▼
┌─────────────────┐
│ Demo Completed  │ Status: "Demo Completed" (completed)
│                 │ Badge: Green/Success
└────────┬────────┘
         │
         ├──→ Convert to Student → Status: "Converted to Student"
         │                         Badge: Green/Success
         │
         └──→ Demo Failed → Status: "Demo Failed" (cancelled)
                           Badge: Red/Danger
```

## Status Display Logic in Sales Page:

### Priority:
1. **If demo_bookings record exists** → Show demo status
2. **If no demo exists** → Show payment/lead status

### Status Mapping:

| Demo Booking Status | Display Text | Badge Color |
|-------------------|--------------|-------------|
| `scheduled` | **Demo Scheduled** | Blue (info) |
| `completed` | **Demo Completed** | Green (success) |
| `converted` | **Converted to Student** | Green (success) |
| `cancelled` | **Demo Cancelled** | Red (danger) |

| Payment Status (no demo) | Display Text | Badge Color |
|-------------------------|--------------|-------------|
| `pending` | **Inquiry** | Yellow (warning) |
| `confirmed` | **Confirmed** | Green (success) |
| `converted` | **Converted to Student** | Green (success) |
| `cancelled` | **Demo Failed** | Red (danger) |

## How to Update Demo Status:

### Method 1: In Sales Page
- Click **"❌ Demo Failed"** → Updates payment status to `cancelled`

### Method 2: In Demos Page (admin.demos)
- Admin can update demo_bookings status:
  - **Scheduled** → **Completed**
  - **Completed** → **Converted** (when student enrolls)
  - **Scheduled** → **Cancelled** (if demo doesn't happen)

## Code Changes Made:

### 1. Database:
- Added `payment_id` to `demo_bookings` table (links lead to demo)
- `email` and `phone` columns already existed

### 2. Models:
```php
// Payment.php
public function demoBookings(): HasMany
public function latestDemo() // Gets most recent demo

// DemoBooking.php
public function payment(): BelongsTo
```

### 3. Controllers:
```php
// AdminController@sales
$leads = Payment::with('latestDemo')->latest()->get();

// DemoBookingController@store
// Creates demo booking linked to payment/lead

// DemoBookingController@updateStatus
// Updates demo status (scheduled → completed → converted)
```

### 4. Views:
```php
// sales/index.blade.php
@if($lead->latestDemo)
  // Show demo status
@else
  // Show payment status
@endif
```

## Admin Workflow:

1. **Lead comes in** → Shows as "Inquiry" (yellow)
2. **Admin clicks "Book Demo"** → Modal opens, fills teacher, date, time
3. **Demo is booked** → Status changes to "Demo Scheduled" (blue)
4. **Demo happens** → Admin goes to Demos page, updates status to "Completed" (green)
5. **Student enrolls** → Admin clicks "Convert to Student", status becomes "Converted to Student" (green)
6. **OR Demo fails** → Admin updates to "Demo Failed" (red)

## Routes:

```php
// Book demo
POST /admin/demos

// Update demo status
PUT /admin/demos/{demoBooking}

// Convert lead to student
POST /admin/sales/{payment}/convert
```
