# BPA Asset Management System — User Manual

> **Version**: 2.0.0 | **Last Updated**: June 2026

---

## Table of Contents

1. [Getting Started](#1-getting-started)
   - [Logging In](#11-logging-in)
   - [Dashboard Overview](#12-dashboard-overview)
   - [User Roles Explained](#13-user-roles-explained)
2. [Managing Assets](#2-managing-assets)
   - [Viewing Assets](#21-viewing-assets)
   - [Adding a New Asset](#22-adding-a-new-asset)
   - [Editing an Asset](#23-editing-an-asset)
   - [Deleting an Asset](#24-deleting-an-asset)
   - [Checking Out an Asset](#25-checking-out-an-asset)
   - [Checking In an Asset](#26-checking-in-an-asset)
   - [Viewing Asset History](#27-viewing-asset-history)
3. [Managing Categories](#3-managing-categories)
   - [Viewing Categories](#31-viewing-categories)
   - [Adding a New Category](#32-adding-a-new-category)
   - [Editing a Category](#33-editing-a-category)
   - [Deleting a Category](#34-deleting-a-category)
4. [Managing Departments](#4-managing-departments)
   - [Viewing Departments](#41-viewing-departments)
   - [Adding a New Department](#42-adding-a-new-department)
   - [Editing a Department](#43-editing-a-department)
   - [Deleting a Department](#44-deleting-a-department)
5. [Managing Users](#5-managing-users)
   - [Viewing Users](#51-viewing-users)
   - [Adding a New User](#52-adding-a-new-user)
   - [Editing a User](#53-editing-a-user)
   - [Deleting a User](#54-deleting-a-user)
   - [Your Profile](#55-your-profile)
6. [Roles & Permissions (Super Admin Only)](#6-roles--permissions)
   - [Managing Roles](#61-managing-roles)
   - [Managing Permissions](#62-managing-permissions)
7. [Reports](#7-reports)
   - [Assets by Department](#71-assets-by-department)
   - [Assets by Status](#72-assets-by-status)
   - [Asset Value Summary](#73-asset-value-summary)
   - [Activity Logs](#74-activity-logs)
8. [QR Scanner](#8-qr-scanner)
   - [Using the Camera Scanner](#81-using-the-camera-scanner)
   - [Manual Lookup](#82-manual-lookup)
   - [Scanner History](#83-scanner-history)
9. [Settings](#9-settings)
   - [Company Settings](#91-company-settings)
   - [Email Settings](#92-email-settings)
     - [Sender Configuration](#921-sender-configuration)
     - [Notification Preferences](#922-notification-preferences)
     - [Sending a Test Email](#923-sending-a-test-email)
   - [Email Logs](#93-email-logs)
   - [Email Health](#94-email-health)
10. [Quick Reference](#10-quick-reference)
    - [Asset Statuses](#101-asset-statuses)
    - [Navigation Guide](#102-navigation-guide)
    - [Keyboard Shortcuts](#103-keyboard-shortcuts)

---

## 1. Getting Started

### 1.1 Logging In

1. Open your web browser and navigate to your BPA Asset Management System URL.
2. You will see the **Sign In** page with the BPA branding on the left.
3. Enter your **Email Address** and **Password**.
4. Check **Remember Me** if you want the system to keep you logged in.
5. Click the **Sign In** button.

> **Forgot your password?** Click the "Forgot Your Password?" link on the login page, enter your email address, and click "Send Password Reset Link". Follow the instructions in the email to reset your password.

### 1.2 Dashboard Overview

After logging in, you land on the **Dashboard**. It gives you a quick snapshot of the system:

**Top Stat Cards (4 cards):**
- **Total Assets** — total number of assets in the system (click to view all assets)
- **Available** — assets ready to be assigned (click to filter by Available status)
- **Assigned** — assets currently checked out (click to filter by Assigned status)
- **In Maintenance** — assets under repair (click to filter by Maintenance status)

**Metric Cards (3 cards):**
- **Total Portfolio Value** — total purchase cost of all assets
- **Retired Assets** — number of decommissioned assets
- **Total System Users** — number of registered users

**Bottom Sections:**
- **Asset Status Distribution** — visual bar showing the proportion of each status
- **Recently Added Assets** — list of the most recently created assets
- **Activity Log** — the 10 most recent system actions

### 1.3 User Roles Explained

The system has three user roles:

| Role | Level | What You Can Do |
|------|-------|-----------------|
| **Super Admin** | Highest | Full access to everything including managing roles and permissions |
| **Admin** | Medium | Full access to assets, categories, departments, users, reports, and settings |
| **Staff** | Basic | View assets and reports, use the QR scanner |

Your role determines which menu items appear in the sidebar and what actions you can perform.

---

## 2. Managing Assets

### 2.1 Viewing Assets

1. Click **Assets** in the sidebar.
2. The assets table shows all assets with the following columns:
   - **Asset Tag** — unique identifier (click to view details)
   - **Image** — thumbnail photo (if available)
   - **Name** — asset name
   - **Category** — category the asset belongs to
   - **Department** — department the asset is assigned to
   - **Assigned To** — person currently using the asset
   - **Status** — current status (colored badge)
   - **Purchase Cost** — how much the asset cost
   - **Actions** — View, Edit, Delete buttons

**Filtering Assets:**
- Use the search bar to find assets by name, tag, or serial number
- Use the dropdown filters for **Category**, **Department**, and **Status**
- Click **Filter** to apply, **Clear** to reset

**Exporting:**
- Click **Excel** to download all assets as a spreadsheet
- Click **PDF** to download as a printable document

### 2.2 Adding a New Asset

1. Click **Assets** in the sidebar.
2. Click the **Add New Asset** button.
3. Fill in the **Asset Information** form:

| Field | Required | Description |
|-------|----------|-------------|
| Asset Name | Yes | A descriptive name for the asset |
| Serial Number | No | The manufacturer's serial number (can be scanned) |
| Category | Yes | Select from existing categories |
| Department | No | Select the owning department |
| Status | Yes | Available, Assigned, Maintenance, or Retired |
| Assign To | No | Select a user if immediately assigning |
| Location | No | Physical location of the asset |
| Purchase Date | No | Date the asset was purchased |
| Purchase Cost | No | Cost of the asset (in USD) |
| Warranty Expiry | No | When the warranty ends |
| Manufacturer | No | Brand or manufacturer name |
| Model | No | Model number or name |
| Description | No | Detailed description |
| Notes | No | Internal notes |

4. Optionally upload an **Asset Image** in the second card.
5. Click **Create Asset** to save.

### 2.3 Editing an Asset

1. Go to **Assets** and find the asset you want to edit.
2. Click the **Edit** (pencil icon) button in the Actions column.
3. Update the fields as needed.
4. To change the image, upload a new one (leave empty to keep the current image).
5. Click **Update Asset** to save changes.

### 2.4 Deleting an Asset

1. Go to **Assets** and find the asset you want to delete.
2. Click the **Delete** (trash icon) button in the Actions column.
3. Confirm the deletion when prompted.

> Deleted assets are **soft-deleted** — they can be recovered by a database administrator if needed.

### 2.5 Checking Out an Asset

When an asset needs to be assigned to a user:

1. Go to **Assets** and click on the **Asset Tag** or **View** (eye icon) to open the asset details.
2. In the **Check Out** card (visible when status is "Available"):
   - Select the user from **Assign To** (required)
   - Select the **Department** (optional)
   - Add any **Notes** (optional)
3. Click **Check Out**.
4. The asset status changes to "Assigned" and a movement record is created.

### 2.6 Checking In an Asset

When an asset is returned:

1. Go to **Assets** and open the asset details.
2. In the **Check In** card (visible when status is "Assigned"):
   - Add any return **Notes** (optional)
3. Click **Check In**.
4. The asset status changes back to "Available" and the assigned user is cleared.

### 2.7 Viewing Asset History

On the asset details page, scroll to the **Movement History** section to see a timeline of all checkouts, check-ins, and transfers for that asset.

---

## 3. Managing Categories

Categories help organize assets by type (e.g., Laptops, Furniture, Electronics).

### 3.1 Viewing Categories

1. Click **Categories** in the sidebar.
2. The table shows:
   - **Name** — category name (click to view)
   - **Assets Count** — how many assets in this category
   - **Description** — brief description
   - **Created** — date created
   - **Actions** — View, Edit, Delete

### 3.2 Adding a New Category

1. Click **Categories** → **Add New Category**.
2. Enter:
   - **Category Name** (required)
   - **Description** (optional)
3. Click **Create Category**.

### 3.3 Editing a Category

1. Find the category and click **Edit**.
2. Update the fields.
3. Click **Update Category**.

### 3.4 Deleting a Category

1. Find the category and click **Delete**.
2. Confirm when prompted.

> Categories with existing assets cannot be deleted until the assets are reassigned to another category.

---

## 4. Managing Departments

### 4.1 Viewing Departments

1. Click **Departments** in the sidebar.
2. The table shows:
   - **Code** — department code
   - **Name** — department name (click to view)
   - **Manager** — department manager
   - **Location** — physical location
   - **Assets Count** — how many assets belong
   - **Actions** — View, Edit, Delete

### 4.2 Adding a New Department

1. Click **Departments** → **Add New Department**.
2. Fill in:
   - **Department Name** (required)
   - **Department Code** (required — a short unique code)
   - **Manager** (optional)
   - **Location** (optional)
   - **Email** (optional)
   - **Phone** (optional)
   - **Description** (optional)
3. Click **Create Department**.

### 4.3 Editing a Department

1. Find the department and click **Edit**.
2. Update the fields.
3. Click **Update Department**.

### 4.4 Deleting a Department

1. Find the department and click **Delete**.
2. Confirm when prompted.

> Departments with existing users or assets cannot be deleted until they are reassigned.

---

## 5. Managing Users

### 5.1 Viewing Users

1. Click **Users** in the sidebar.
2. The table shows:
   - **Name** — user's full name with avatar (click to view)
   - **Email** — email address
   - **Employee ID** — company employee identifier
   - **Department** — assigned department
   - **Role** — user role (colored badge)
   - **Assets** — number of currently assigned assets
   - **Actions** — View, Edit, Delete

**Filtering:**
- Search by name, email, or employee ID
- Filter by **Role** or **Department**

### 5.2 Adding a New User

1. Click **Users** → **Add New User**.
2. Fill in **Personal Information**:
   - **Full Name** (required)
   - **Email Address** (required)
   - **Employee ID** (optional)
   - **Phone** (optional)
   - **Position** (optional)
   - **Avatar** (optional — upload a profile picture)
3. Fill in **Account Settings**:
   - **Password** (required)
   - **Confirm Password** (required)
   - **Role** (required — Super Admin, Admin, or Staff)
   - **Department** (optional)
4. Click **Create User**.

### 5.3 Editing a User

1. Find the user and click **Edit**.
2. Update the fields as needed (leave password blank to keep current password).
3. Click **Update User**.

### 5.4 Deleting a User

1. Find the user and click **Delete**.
2. Confirm when prompted.

> Users with currently assigned assets cannot be deleted. Check in their assets first.

### 5.5 Your Profile

1. Click your name/avatar in the top-right corner.
2. Select **Profile** from the dropdown.
3. You can:
   - Update your avatar, name, email, phone, and position
   - Change your password (leave blank to keep current)
   - View your **My Assigned Assets** table showing what's checked out to you
4. Click **Update Profile** to save.

---

## 6. Roles & Permissions

> This section is only visible to **Super Admin** users.

### 6.1 Managing Roles

1. Click **Roles** in the sidebar (or navigate through Settings > sidebar).
2. The table shows:
   - **Name** — role display name
   - **Slug** — internal identifier
   - **Users** — number of users with this role
   - **Permissions** — number of assigned permissions
   - **Type** — System (built-in) or Custom
   - **Actions** — Edit, Delete (custom roles only)

**Creating a New Role:**

1. Click **Add New Role**.
2. Enter:
   - **Role Name** (required — e.g., "IT Technician")
   - **Role Slug** (required — e.g., "it_technician")
   - **Description** (optional)
3. In the **Assign Permissions** section, check the boxes for each permission you want the role to have. Permissions are grouped by category (e.g., assets, users, reports).
4. Click **Create Role**.

**Editing a Role:**

1. Click the **Edit** button next to a role.
2. Update the name or description (slug is read-only for system roles).
3. Update permission checkboxes.
4. Click **Update Role**.

**Deleting a Role:**

1. Click the **Delete** button (only available for custom roles, not system roles).
2. Confirm when prompted.

### 6.2 Managing Permissions

1. Click **Permissions** in the sidebar.
2. The table shows:
   - **Name** — permission display name
   - **Slug** — dotted key (e.g., `assets.create`)
   - **Assigned Roles** — how many roles include this permission
   - **Type** — System or Custom
   - **Actions** — Edit, Delete (custom only)

**Creating a New Permission:**

1. Click **Add New Permission**.
2. Enter:
   - **Permission Name** (required — e.g., "Export Assets")
   - **Permission Slug** (required — use dotted keys like `assets.export`)
   - **Description** (optional)
3. Click **Create Permission**.

**Editing a Permission:**
1. Click **Edit**.
2. Update the fields (slug is read-only for system permissions).
3. Click **Update Permission**.

---

## 7. Reports

### 7.1 Assets by Department

Shows how assets are distributed across departments with total values.

1. Click **Reports** → **Assets by Department**.
2. View the table showing each department, code, location, asset count, and total value.
3. Click **Excel** or **PDF** to export.

### 7.2 Assets by Status

Shows asset count and value grouped by status with percentages.

1. Click **Reports** → **Assets by Status**.
2. View each status with count, total value, and a progress bar showing percentage.
3. Click **Excel** or **PDF** to export.

### 7.3 Asset Value Summary

Shows the total portfolio value with breakdowns.

1. Click **Reports** → **Asset Value Summary**.
2. View KPI cards: Total Assets, Total Value, Active Value, Retired Value.
3. The table below shows value by asset category.
4. Click **Excel** or **PDF** to export.

### 7.4 Activity Logs

View a complete audit trail of all system actions.

1. Click **Reports** → **Activity Logs**.
2. Filter by:
   - **Action** — All, Created, Updated, Deleted
   - **User** — specific user
   - **Date From / Date To** — date range
3. Click **Filter** to apply, **Clear** to reset.
4. The table shows: User, Action (colored badge), Model, IP Address, and Date.
5. Click **Export PDF** to download.

---

## 8. QR Scanner

### 8.1 Using the Camera Scanner

1. Click **Scanner** in the sidebar.
2. On the left panel, select the scan type:
   - **QR Code** — scan printed QR labels
   - **Serial** — scan barcodes on equipment
   - **Tag** — scan asset tag barcodes
3. Click **Start Scanning** and allow camera access when prompted.
4. Point the camera at the QR code or barcode.
5. Once scanned, the asset details appear on the right.
6. Click **View Full Details** to open the asset page, or **Clear** to start over.

### 8.2 Manual Lookup

1. On the Scanner page, use the right panel **Manual Lookup**.
2. Type a QR code, asset tag, or serial number (starts searching after 2 characters).
3. Select the matching asset from the suggestions.
4. Asset details appear below.

### 8.3 Scanner History

1. Click **Scanner** → **Scanner History** (or the history link on the scanner page).
2. View a log of all scans with:
   - Asset name and tag
   - Scan type (QR Code, Serial, Tag)
   - Who scanned it (or "Public / Guest")
   - Device and IP address
   - Date and time
3. Click the **View** (eye icon) to open the asset.

---

## 9. Settings

### 9.1 Company Settings

1. Click **Settings** → **Company Settings**.
2. Configure:
   - **Company Name** — appears in reports and system footer
   - **Company Email** — contact email
   - **Company Phone** — contact phone number
   - **Timezone** — set your local timezone (default: Pacific/Tarawa)
   - **Company Address** — physical address
   - **Company Logo** — upload your organization's logo (max 2MB)
3. Click **Save Settings**.

### 9.2 Email Settings

The Email Settings page lets you configure how the system sends email notifications. Access it by clicking **Settings** → **Email Settings**.

#### 9.2.1 Sender Configuration

Configure the email identity used for all outgoing system messages:

1. Under **Email Sender Settings**, set:
   - **From Address** — the email address that appears in the From field (e.g., `no-reply@bpa-app.net`)
   - **From Name** — the display name recipients will see
2. Click **Save Settings**.

> The Resend API key and mail driver are configured in the server environment by your system administrator.

#### 9.2.2 Notification Preferences

Control which system events trigger email notifications:

1. Under **Notification Settings**, toggle the **Enable Email Notifications** master switch to turn all notifications on or off.
2. Set **Notification Recipients** — a comma-separated list of email addresses that will receive all notifications (e.g., `admin@bpa.com, manager@bpa.com`). If left empty, the system uses the From Address.
3. Configure individual notification types by toggling each switch:

   | Toggle | Triggers When |
   |--------|---------------|
   | **Asset Created** | A new asset is added to the system |
   | **Asset Updated** | Asset details are modified |
   | **Asset Checkout / Check-in** | An asset is assigned to or returned by a user |
   | **User Changes** | User accounts are created or updated |
   | **Maintenance Alerts** | Asset maintenance becomes due or overdue |

4. Click **Save Settings** to apply your preferences.

#### 9.2.3 Sending a Test Email

Verify that the email system is working correctly:

1. Under **Send Test Email**, enter a recipient email address.
2. Click **Send Test Email**.
3. A notification will appear indicating success or failure.
4. Check the recipient's inbox for the test message.

> If the test fails, check the [Email Logs](#93-email-logs) for error details, or visit the [Email Health](#94-email-health) page to diagnose configuration issues.

### 9.3 Email Logs

The Email Logs page provides a complete history of all email notifications sent by the system. Access it from **Settings** → **Email Logs** or via the sidebar.

**Statistics cards** at the top show:
- **Total Emails** — all logged email activity
- **Sent** — successfully delivered emails
- **Queued** — emails waiting to be sent
- **Failed** — emails that could not be delivered

**Filtering and Searching:**
- Use the **search bar** to find entries by recipient email or subject
- Use the **Status** dropdown to filter by Queued, Sent, or Failed
- Use the **Type** dropdown to filter by notification category (e.g., `asset.created`, `user.updated`)
- Click **Filter** to apply, or adjust filters to refine results

**Managing Log Entries:**
- Click the **eye** icon to view full details including timestamps, message IDs, and error messages
- Click the **trash** icon to delete a single entry
- Click **Clear All** to remove all log entries (requires confirmation)

> Failed log entries include the error message from the email provider, which helps administrators diagnose delivery issues.

### 9.4 Email Health

The Email Health page gives you a real-time overview of the email system's configuration and performance. Access it from **Settings** → **Email Health** or via the sidebar.

**Configuration Status** panel shows:
- **Mail Driver** — current mail transport (should be `resend` for production)
- **Resend API Key** — whether the API key is configured
- **From Address / From Name** — the sender identity
- **Queue Connection** — the queue driver used for processing
- **Notifications Enabled** — whether notifications are turned on
- **Notification Recipients** — who will receive alerts

**Delivery Statistics** panel shows:
- Total emails sent and failed
- Today's sent and failed counts

**Recent Activity** table lists the most recent email log entries with timestamps, recipients, subjects, and statuses for quick monitoring.

**Send Test Email** form allows you to quickly verify delivery from this page as well.

---

## 10. Quick Reference

### 10.1 Asset Statuses

| Status | Badge Color | Meaning |
|--------|-------------|---------|
| **Available** | Green | Asset is ready to be assigned |
| **Assigned** | Blue | Asset is currently checked out to a user |
| **Maintenance** | Amber/Yellow | Asset is under repair |
| **Retired** | Gray | Asset has been decommissioned |

### 10.2 Navigation Guide

| Sidebar Link | Icon | Who Can See It |
|-------------|------|----------------|
| Dashboard | tachometer | Everyone |
| Assets | boxes | Everyone |
| Categories | tags | Admin+ |
| Departments | building | Admin+ |
| Users | users | Admin+ |
| Roles | user-shield | Super Admin only |
| Permissions | key | Super Admin only |
| Reports | chart-bar | Everyone |
| Scanner | qrcode | Everyone with scanner permission |
| Settings | cog | Admin+ |
| Email Logs | history | Admin+ |
| Email Health | heartbeat | Admin+ |

### 10.3 Keyboard Shortcuts

- **Tab / Shift+Tab** — navigate between form fields
- **Enter** — submit the current form
- **Escape** — close modals

---

## Need Help?

If you encounter any issues:

1. Check your internet connection and try refreshing the page.
2. Ensure you have the correct role/permissions for the action you're trying to perform.
3. Contact your system administrator for further assistance.

---

**Built with ❤️ for BPA (Broadcasting & Publications Authority)**
