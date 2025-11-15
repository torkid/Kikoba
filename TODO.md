 JukwaaSaaS: Implementation TODO List (Affiliate Network Model)
This plan is revised based on the "Affiliate Network" model. The "Admin" provides all content (videos, templates, domains), and "Tenants" (Affiliates) drive traffic using unique links.
Phase 1: The Core Foundation (MVP)
Goal: To build the absolute minimum viable product to prove the core loop: a single affiliate can be credited for a sale of a pre-built product. We will hard-code one Admin-created template and one test affiliate.
[ ] Task 1.1: Setup Project & Core Database Schema
AC: A code repository (e.g., Git) is initialized.
AC: A backend framework (e.g., Node.js/Express) and frontend framework (e.g., Next.js) are installed.
AC: A PostgreSQL database is created.
AC: The initial core tables are created: tenants (affiliates), templates (Admin-created), videos (Admin-links), transactions, domains (Admin-owned).
[ ] Task 1.2: Implement Basic Tenant (Affiliate) Authentication
AC: A user can register for a "Tenant" (Affiliate) account (FR-AUTH-1).
AC: Passwords are securely hashed (FR-AUTH-2).
AC: A registered affiliate can log in and log out (FR-AUTH-3).
AC: The system sends a verification email upon registration (FR-AUTH-6).
AC: Secure session management (e.g., JWT) is in place (FR-AUTH-5).
[ ] Task 1.3: Create a Single Hard-Coded Public Page
AC: A single, public-facing page is created (e.g., /t/test-link).
AC: This page manually pulls configuration for one "test" template (e.g., Price = TSH 1000) and one "test" affiliate.
AC: The page displays the Admin-provided videos and a "Pay" button (FR-PUB-3).
[ ] Task 1.4: Implement Payment Initiation (ZenoPay)
AC: Clicking "Pay" on the demo page submits a phone number to the backend.
AC: The backend successfully calls the ZenoPay Payment API with the correct amount and a unique order_id (FR-TRX-1).
AC: A new record is created in the transactions table with status: 'PENDING' and must link to the test affiliate's ID (FR-TRX-1).
AC: The frontend shows a "Waiting for payment..." message.
[ ] Task 1.5: Implement Payment Confirmation (Webhook)
AC: A secure webhook endpoint (e.g., /api/webhooks/zenopay) is created (FR-TRX-2).
AC: The endpoint must validate the ZenoPay signature (FR-TRX-2a).
AC: Upon receiving a valid "success" webhook, the system finds the PENDING transaction by its order_id and updates its status to COMPLETED (FR-TRX-2b).
AC: The system responds to ZenoPay with an HTTP 200 within 2 seconds (NFR-3).
[ ] Task 1.6: Implement Basic Content Access
AC: After a transaction is COMPLETED, the system grants the End-User access (e.g., by setting a secure cookie) (FR-PUB-4).
AC: When the End-User refreshes the page, the system checks for this cookie.
AC: If the cookie is valid, the page displays the actual videos instead of the paywall.
Phase 2: Affiliate Dashboard & Dynamic Link Generation
Goal: To build the self-service dashboard for Affiliates to generate their links and track their stats.
[ ] Task 2.1: Implement Dynamic Link Routing
AC: The backend server is configured to handle dynamic routes (e.g., /t/[unique_code]).
AC: The system can correctly parse a unique affiliate link (e.g., https://kuma-tamuu.icu/t/ezZh...) and identify both the tenant_id (affiliate) and the template_id from the unique_code.
AC: Accessing a non-existent link (e.g., /t/invalid) returns an HTTP 404 "Not Found" page.
[ ] Task 2.2: Build the Tenant (Affiliate) Dashboard Shell
AC: A secure section (e.g., /dashboard) is created, requiring a valid Tenant login (from 1.2).
AC: All API requests to /api/dashboard/* must be scoped by the authenticated Tenant's tenant_id (NFR-SEC-5).
AC: The dashboard has navigation for "Link Generator," "Analytics," and "Wallet."
[ ] Task 2.3: Implement Affiliate Link Generator
AC: A "Link Generator" page exists in the dashboard (Replaces "Site Settings").
AC: The Tenant can select from a dropdown list of Admin-provided Domains (e.g., kuma-tamuu.icu, makojozii-tupu.icu).
AC: The Tenant can select from a visual list of Admin-provided Templates (e.g., "Template XTube," "Template X Lite").
AC: Clicking "Generate Page" creates a new unique, shareable link (e.g., https://kuma-tamuu.icu/t/CODE-FOR-AFFILIATE-AND-TEMPLATE).
AC: The Tenant can see a list of their generated links and a "Copy" button.
[ ] Task 2.4: Connect Routing to Public Sites (Dynamic)
AC: The public-facing site logic (from 1.3) is now fully dynamic.
AC: Visiting a unique affiliate link (e.g., .../t/ezZh...) correctly fetches the price and videos for the chosen template.
AC: The payment flow (from 1.4) correctly identifies the tenant_id (affiliate) from the unique link and associates the transaction with them for commission (FR-TRX-1).
[ ] Task 2.5: Implement Basic Affiliate Analytics
AC: Every page view on a unique affiliate link logs an entry in the analytics_views table with the correct tenant_id (FR-PUB-2).
AC: The Tenant Dashboard "Analytics" page correctly displays their "Total Clicks/Views" and "Total Sales" (Conversions) (FR-TEN-1).
Phase 3: Financial Core & Admin Management
Goal: To build the complete financial loop (commission) and the Admin tools to manage the platform, content, and payouts.
[ ] Task 3.0: Build Admin Content & Campaign Management
AC: An Admin Dashboard (see 3.3) must exist.
AC: The Admin has a "Domains" page to add/remove the list of available domains (e.g., the 5 wildcard domains).
AC: The Admin has a "Templates" page to create/edit templates.
AC: When creating/editing a template, the Admin must be able to set the price and attach the video links that will be shown.
[ ] Task 3.1: Implement Revenue Splitting & Balance Logic
AC: The Payment Webhook (from 1.5) is upgraded.
AC: It must fetch the platform_commission_rate from admin_settings (FR-ADMIN-5).
AC: It correctly calculates platform_fee and tenant_earnings for the transaction (FR-TRX-2b).
AC: It atomically increments the tenants.balance column (for the correct affiliate) with the tenant_earnings amount (FR-TRX-2b).
[ ] Task 3.2: Implement Tenant Wallet & Payout Request
AC: The "Wallet" page in the Tenant Dashboard now shows the correct Current Balance from tenants.balance (FR-TEN-4).
AC: The Tenant can set and update their payout_phone number (FR-TEN-4).
AC: A "Request Payout" button is enabled only if balance >= admin_settings.min_payout_amount (FR-TEN-4).
AC: Clicking it (1) debits the tenants.balance and (2) creates a new payouts record with status: 'PENDING' (FR-PAYOUT-1). This must be an atomic database transaction.
[ ] Task 3.3: Build Admin Dashboard & User Management
AC: A separate, secure login exists for admin_users (FR-ADMIN-1).
AC: The Admin Dashboard shows global analytics (Total Revenue, Total Fees) (FR-ADMIN-1).
AC: The Admin can view a list of all Tenants (Affiliates) and deactivate them (FR-ADMIN-2).
AC: The Admin can manage platform settings (like commission rate) (FR-ADMIN-5).
[ ] Task 3.4: Build Payout Approval Queue (Manual Security)
AC: The Admin Dashboard has a "Payouts" page showing all payouts with status: 'PENDING' (FR-ADMIN-3).
AC: The Admin can view details for each request (Tenant name, phone, amount) (FR-PAYOUT-2).
AC: The Admin has "Approve" and "Reject" buttons.
AC: "Approve" changes payouts.status to APPROVED.
AC: "Reject" changes payouts.status to FAILED and atomically credits the amount back to the tenants.balance (FR-PAYOUT-4).
[ ] Task 3.5: Implement Payout Processing (Disbursement API)
AC: A new, secure "Process Approved Payouts" function/button is available to the Admin.
AC: When triggered, the backend fetches all APPROVED payouts.
AC: For each payout, it calls the ZenoPay Disbursement API (walletcashin/process/) (FR-PAYOUT-3).
AC: The ZenoPay pin and x-api-key must be read only from secure environment variables (NFR-SEC-1).
AC: On ZenoPay "success", the payouts.status is set to COMPLETED (FR-PAYOUT-4).
AC: On ZenoPay "error" (e.g., Insufficient Balance), the payouts.status is set to FAILED, the amount is credited back to tenants.balance, and the Admin is notified (FR-PAYOUT-4).
Phase 4: Security, Scaling, & Polish (Production Hardening)
Goal: To move the platform from a "functional" state to a "production-ready" state, focusing on security, performance, and reliability.
[ ] Task 4.1: Implement Full Security Model
AC: Rate limiting is applied to all auth endpoints (login, register, password reset) and payment endpoints (NFR-SEC-8).
AC: Anti-CSRF tokens are implemented on all form submissions (NFR-SEC-3).
AC: All database queries are reviewed and confirmed to be parameterized (NFR-SEC-3) and tenant-scoped (NFR-SEC-5).
AC: End-User phone numbers are now hashed in the transactions table (NFR-SEC-6).
[ ] Task 4.2: Implement Financial Auditing
AC: An audit_log table is created.
AC: All critical Admin actions (payout approval, rejection, manual balance adjustments) must create a new, immutable record in the audit log (NFR-SEC-7).
[ ] Task 4.3: Implement Performance & Reliability Features
AC: A production database backup and restore strategy is in place (NFR-RL-2).
AC: A /health check endpoint is created for uptime monitoring (NFR-RL-3).
AC: Critical columns (tenant_id, order_id, affiliate link codes) are indexed in the database (NFR-4).
AC: A daily reconciliation job is created to check for PENDING transactions older than 1 hour (FR-TRX-2c).
[ ] Task 4.4: Implement Usability & Onboarding Polish
AC: A "Setup Wizard" is created for new Tenants to guide them through their first link generation (NFR-US-1).
AC: Help tooltips (?) are added to all metrics on the Tenant Dashboard (NFR-US-3).
AC: All user-facing error messages are reviewed and made user-friendly (4.1).
AC: All transactional emails (Welcome, Password Reset, Payout Confirmation) are designed and implemented (4.4).
