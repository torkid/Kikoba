Software Requirements Specification (SRS)
JukwaaSaaS: A Multi-Tenant Affiliate Link Platform
Version: 1.1 (Affiliate Model)
Date: November 15, 2025
Prepared by: [Your Name Here]
1. Introduction
1.1 Purpose
The purpose of this document is to comprehensively detail all systemic, functional, technical, and non-functional requirements for the "JukwaaSaaS" platform. This system is a "Software as a Service" (SaaS) solution architected to empower affiliates and marketers (hereafter "Tenants") to monetize the platform's pre-built video templates.
The platform provides a user-friendly, non-technical interface for Tenants to create an account, select a domain, select a pre-built template, set their own price, and instantly generate unique, trackable affiliate links. The core business goal is to create a scalable revenue stream shared between the platform owner ("Admin") and the "Tenants". It achieves this by managing the entire end-to-end process: from collecting payments from customers ("End-Users") via ZenoPay to calculating commissions and distributing the "Tenants'" earnings via ZenoPay Disbursements.
1.2 Project Scope
This project entails the development of a complete, end-to-end, cloud-native system. The core components included in this scope are:
Tenant (Affiliate) Onboarding: A public-facing sign-up and login system.
Tenant Dashboard: A secure, private administrative area where "Tenants" generate their unique affiliate links, view analytics (clicks, conversions), and manage their earnings and payout requests.
Admin Dashboard: A high-privilege, global administrative interface for the platform "Admin" to manage all Tenants, approve/reject payouts, manage the list of domains, templates, and video links, configure platform settings, and view system-wide financial analytics.
Dynamic Link Generation & Routing Engine: A dynamic rendering system responsible for serving the correct template/content based on a unique affiliate link code and routing from multiple Admin-owned domains.
Financial Transaction Module: Full, secure, backend-only integration with the ZenoPay API suite.
Out of Scope (for Version 1.0):
Tenant Content Uploads: This is not a feature. The "Admin" is solely responsible for all content (videos, templates). "Tenants" are marketers, not creators.
Custom Domain Mapping: "Tenants" cannot add their own custom domains. They must select from the list of domains provided by the "Admin".
Advanced Template Customization: "Tenants" cannot edit the templates. Their only customization is setting the price for their generated link.
1.3 Definitions, Acronyms, and Abbreviations
Admin (Owner): The primary administrator of the entire platform.
Affiliate Link: The unique, trackable URL a Tenant generates (e.g., domain.com/t/xyz123).
API: Application Programming Interface
Disbursement: The process of sending funds from the platform's central account to a "Tenant".
End-User (Customer): A public visitor who clicks an Affiliate Link and pays for content.
FR: Functional Requirement.
JukwaaSaaS: The designated name for this project.
Multi-Tenancy: A single software architecture serving multiple "Tenants" while securely isolating their data.
NFR: Non-Functional Requirement.
Paywall: An electronic barrier requiring payment from an "End-User" to access content.
SaaS: Software as a Service.
SRS: Software Requirements Specification.
Tenant (Affiliate/Marketer): A registered user of the platform (an affiliate) who generates links to drive traffic.
Webhook: An automated HTTP POST callback from ZenoPay to JukwaaSaaS to notify of an event.
1.4 References
ZenoPay Payment API Documentation
ZenoPay Disbursement API Documentation
ZenoPay Webhook Integration Guide
2. Overall Description
2.1 Product Perspective
JukwaaSaaS is a standalone, cloud-hosted web application architected for multi-tenancy. It will consist of three primary software components:
Frontend Application: A Single Page Application (SPA) (e.g., built with React/Next.js) that serves the public-facing marketing site (for attracting new Tenants) and the secure "Tenant" and "Admin" dashboards.
Backend API: A stateless RESTful API (e.g., built with Node.js/Express) that handles all business logic, authentication, database interactions, and secure communication with ZenoPay.
Central Database: A relational database (e.g., PostgreSQL) that stores all platform data: tenant accounts, admin-owned domains, admin-owned templates, video links, generated affiliate links, and transaction ledgers, all strictly segregated by tenant_id where appropriate.
2.2 Product Functions
The high-level functions of the platform are:
Tenant Registration: Allow "Tenants" (Affiliates) to create and manage their accounts.
Campaign & Content Management (Admin): Allow the "Admin" to manage all domains, templates, and video links.
Affiliate Link Generation (Tenant): Provide an interface for "Tenants" to select a domain, select a template, set a price, and generate a unique, shareable link.
Payment Collection: Securely process payments from "End-Users" who visit an affiliate link.
Revenue Management: Automatically track all sales, attribute them to the correct "Tenant", calculate the platform's commission, and maintain an accurate, real-time balance of "Tenant" earnings.
Payout Processing: Allow "Tenants" to request a withdrawal of their available balance and the "Admin" to approve and process these payouts via the ZenoPay Disbursement API.
Analytics: Provide a simple, visual analytics dashboard for "Tenants" showing link clicks and sales (conversions).
Platform Administration: Provide a comprehensive dashboard for the "Admin" to manage all Tenants, financial operations, and all platform assets (domains, templates).
2.3 User Classes and Characteristics
Platform Administrator (Admin):
Profile: The platform owner.
Responsibilities: System health, financial management (approving payouts), Tenant management, and sole responsibility for managing all domains, templates, and video content.
Access: Global read/write access to all system data.
Tenant (Affiliate/Marketer):
Profile: The primary user (customer) of the SaaS.
Skills: May be non-technical. Values simplicity and clear stats.
Goal: To quickly generate high-converting affiliate links, drive traffic to them, and get paid for conversions.
Access: Strictly limited to their own data (their generated links, their stats, their sales, their balance).
End-User (Customer):
Profile: A public, anonymous visitor to an "Affiliate Link".
Goal: To gain access to paywalled content quickly and securely.
Expectations: Expects immediate access to the video(s) after a successful payment.
2.4 Operating Environment
System: The system will be entirely web-based and must function correctly on all modern, evergreen browsers.
Backend: A server-side application (Recommended: Node.js w/ Express or NestJS) is preferred for its asynchronous, non-blocking I/O model.
Database: A relational database (Recommended: PostgreSQL) is strongly preferred for ACID-compliant financial transactions.
Frontend: A modern JavaScript framework (Recommended: React/Next.js).
Hosting: A cloud platform (e.g., Vercel, AWS) that supports Node.js applications, PostgreSQL databases, and wildcard DNS routing for multiple domains.
2.5 Design and Implementation Constraints
ZenoPay Integration: The system must use ZenoPay as the exclusive provider for payment and disbursement.
API/PIN Security: The ZenoPay Disbursement API Key and PIN must be stored as secure, encrypted environment variables on the backend. They must never be exposed in frontend code.
Multi-Tenancy: The architecture must enforce database-level multi-tenancy for all Tenant-specific data (links, transactions, balances).
Domains & Links: The system must be able to accept traffic from multiple, Admin-owned domains (e.g., domain1.icu, domain2.icu) and route traffic based on a unique path or code (e.g., /t/xyz123).
Financial Precision: All financial data (prices, balances, fees) must be stored using the DECIMAL data type.
Stateless Backend: The backend API must be stateless to allow for horizontal scaling.
2.6 Assumptions and Dependencies
The "Admin" owns and has full legal and financial access to a ZenoPay merchant account with both Payment and Disbursement APIs enabled.
The "Admin" owns and has DNS control over all (e.g., 5+) domains to be used.
ZenoPay provides real-time, reliable Webhooks for transaction confirmation.
The "Admin" bears the sole legal responsibility for all video content provided on the templates.
3. Specific Requirements
3.1 System Architecture
FR-SYS-1 (Multi-Tenancy): The system will implement a "Single Application, Single Database" (SASD) multi-tenancy model. Each "Tenant" (Affiliate) will be assigned a unique tenant_id. All data generated by or for a tenant (e.g., generated_links, transactions, analytics_views) must be scoped with their tenant_id column.
FR-SYS-2 (Dynamic Link Routing): The server must be able to receive requests from any of the Admin-owned domains. It must parse the URL path to find a unique_link_code (e.g., /t/[unique_link_code]).
It must use this unique_link_code to look up the generated_links record in the database.
This record will provide the tenant_id (for commission), the template_id (for content), and the price (for the paywall).
If no link is found, it must return an HTTP 404 "Not Found" page.
3.2 User Authentication ("Tenant")
FR-AUTH-1 (Registration): A "Tenant" must be able to register for an account using an Email, Password, and a unique affiliate_id (their "API Account" ID).
FR-AUTH-2 (Password Security): The system must store passwords using a strong hashing algorithm (e.g., Bcrypt).
FR-AUTH-3 (Login/Logout): A "Tenant" must be able to log in and log out.
FR-AUTH-4 (Session Management): The system will use JSON Web Tokens (JWTs) stored in secure, HttpOnly cookies.
FR-AUTH-5 (Email Verification): Payout features must be disabled until a Tenant has verified their email address.
3.3 "Tenant Dashboard"
FR-TEN-1 (Analytics): Upon login, the "Tenant" must see a dashboard with key analytics:
Total Clicks/Views (per link / total).
Total Sales (Conversions) (per link / total).
Conversion Rate (%).
Total Net Earnings.
Current Available Balance (for Payout).
FR-TEN-2 (Link Generator): The "Tenant" must have a "Generate Page" interface (see image image_59afe5.png):
An input for their API Account (affiliate_id).
An input to set the Amount (Price) for this link.
A dropdown to "Select Domain" (populated from domains table, FR-ADMIN-4).
A visual list to "Select Template" (populated from templates table, FR-ADMIN-4).
A "Generate Page" button.
Upon success, the system must create a new generated_links record and display the full, unique, shareable link (e.g., https://kuma-tamuu.icu/t/ezZh...) with a "Copy" button.
FR-TEN-3 (Wallet & Payouts): The "Tenant" must have a "Wallet" page to:
Set or update their mobile Payout Phone Number.
View their "Current Balance" and "Pending Payout" amount.
Request a payout if their balance is above the "Admin"-defined minimum threshold.
View their payout history (PENDING, COMPLETED, FAILED).
FR-TEN-4 (Sales History): The "Tenant" must be able to see a paginated list of their completed sales transactions, showing date, amount, commission_earned, and the link_id that generated the sale.
3.4 Public-Facing Affiliate Sites
FR-PUB-1 (Dynamic Content): The system must render the correct template (videos, layout) based on the template_id resolved from the unique_link_code.
FR-PUB-2 (Dynamic Price): The paywall must display the price set by the "Tenant" (stored in the generated_links record).
FR-PUB-3 (Analytics): Each page view on this site must be recorded in the database and associated with the correct generated_link_id and tenant_id.
FR-PUB-4 (Access): After a successful payment, the system must grant the "End-User" access to the content (e.g., via a secure session).
3.5 Payment & Transaction Logic
FR-TRX-1 (Transaction Initiation):
When an "End-User" clicks "Pay", the request is sent to the Backend.
The Backend must identify the generated_link_id and thus the tenant_id and price.
The Backend must generate a unique order_id.
The Backend must save this transaction to a transactions table with status: 'PENDING', the associated tenant_id, and generated_link_id.
The Backend must send the payment request to the ZenoPay Payment API using this order_id, the price from the link, and the "End-User's" phone number.
FR-TRX-2 (Transaction Confirmation - Webhook):
The system must provide a secure Webhook endpoint for ZenoPay.
FR-TRX-2a (Security): The webhook must be validated (e.g., via signature).
FR-TRX-2b (Processing): Once validated, the system must look up the transaction by order_id.
If the payment is successful (COMPLETED):
Update the status to COMPLETED.
Calculate the platform_fee (based on admin_settings.commission_rate).
Calculate the tenant_earnings (amount - platform_fee).
Atomically (within a database transaction) increment the tenants.balance of the correct affiliate (tenant_id).
3.6 Payout / Disbursement Logic
FR-PAYOUT-1 (Request): When a "Tenant" requests a payout, the system must (within a database transaction):
Verify the amount does not exceed their tenants.balance.
Debit the amount from their tenants.balance.
Create a new record in the payouts table with status: 'PENDING'.
FR-PAYOUT-2 (Admin Approval - SECURITY): The system must not process payouts automatically.
The "Admin" must see a list of all PENDING payout requests.
The "Admin" must have "Approve" or "Reject" buttons.
FR-PAYOUT-3 (Processing): When the "Admin" clicks "Approve":
The Backend must update the payouts.status to APPROVED.
It must initiate an asynchronous request to the ZenoPay Disbursement API (walletcashin/process/).
The request must contain the transid, utilitycode: "CASHIN", utilityref (the "Tenant's" payout phone), amount, and the "Admin's" secret pin.
FR-PAYOUT-4 (Confirmation/Failure):
On "success", update payouts.status to COMPLETED.
On "error", update payouts.status to FAILED, and the system must credit the amount back to the tenants.balance. The "Admin" must be notified.
3.7 "Admin Dashboard"
FR-ADMIN-1 (Global Analytics): "Admin" must see platform-wide analytics (Total Revenue, Total Platform Fees, Total Payouts).
FR-ADMIN-2 (Tenant Management): "Admin" must be able to search, view, and deactivate "Tenant" (Affiliate) accounts.
FR-ADMIN-3 (Payout Approval Queue): "Admin" must see and manage the Payouts queue.
FR-ADMIN-4 (Campaign Management): The "Admin" must have pages to manage:
Domains: Add/remove the list of available domains (e.g., kuma-tamuu.icu).
Templates: Create/edit templates (e.g., "Template XTube").
Videos: A "Video Library" where the Admin adds/manages all video links.
When creating a template, the Admin must be able to select videos from the Library to attach to it.
FR-ADMIN-5 (Settings): "Admin" must be able to set global settings (Platform commission rate, Minimum payout amount).
3.8 Database Schema (Revised Example)
admin_users: id (PK), email, password_hash, role
tenants: id (PK), affiliate_id (UNIQUE, "API Account"), email (UNIQUE), password_hash, payout_phone, balance (DECIMAL, default: 0), is_active (BOOLEAN)
domains: id (PK), domain_name (UNIQUE, e.g., kuma-tamuu.icu), is_active (BOOLEAN)
videos: id (PK), title, video_url (Admin-owned link), thumbnail_url
templates: id (PK), name, description, thumbnail_url
template_videos: template_id (FK), video_id (FK) (Links videos to templates)
generated_links: id (PK), link_code (UNIQUE, e.g., ezZh...), tenant_id (FK), domain_id (FK), template_id (FK), price (DECIMAL, set by tenant)
transactions: id (PK), tenant_id (FK), generated_link_id (FK), order_id (UNIQUE), end_user_phone_hash, amount (DECIMAL), platform_fee (DECIMAL), tenant_earnings (DECIMAL), status (ENUM: 'PENDING', 'COMPLETED', 'FAILED')
payouts: id (PK), tenant_id (FK), amount (DECIMAL), status (ENUM: 'PENDING', 'APPROVED', 'COMPLETED', 'FAILED'), zenopay_trans_id
analytics_views: id (PK), generated_link_id (FK), tenant_id (FK), timestamp, ip_hash
admin_settings: id (PK), setting_name (UNIQUE), setting_value (VARCHAR) (e.g., 'commission_rate', '0.10')
4. External Interface Requirements
4.1 User Interfaces
The platform must be fully responsive (mobile-first).
The "Link Generator" must be extremely simple and match the layout from image_59afe5.png.
The "Tenant Dashboard" must clearly separate Clicks, Conversions, and Balance.
4.2 Software Interfaces
ZenoPay Payment API: To initiate "End-User" transactions.
ZenoPay Disbursement API: To process "Tenant" payouts.
ZenoPay Webhooks: To consume real-time payment confirmations.
4.3 Communications Interfaces
All communication must be over HTTPS.
All transactional emails (Welcome, Password Reset, Payout Confirmation) must be implemented.
5. Non-Functional Requirements
5.1 Performance
NFR-1: Affiliate links must load in under 3 seconds.
NFR-2: The Webhook endpoint must respond to ZenoPay in under 2 seconds.
NFR-3: Database queries for analytics must be optimized for speed.
5.2 Security
NFR-SEC-1 (Critical): ZenoPay API Keys and Disbursement PIN must be stored as encrypted environment variables.
NFR-SEC-2: All passwords must be hashed.
NFR-SEC-3: The system must be protected against XSS, CSRF, and SQL Injection.
NFR-SEC-4: Webhook authenticity must be verified.
NFR-SEC-5 (Data Segregation): All Tenant-facing API endpoints must be strictly scoped by the authenticated tenant_id.
NFR-SEC-6 (Link Security): The unique_link_code must be cryptographically random and unguessable.
NFR-SEC-7 (Auditing): All financial operations (payout approvals/rejections) must be logged.
NFR-SEC-8 (Rate Limiting): Must be applied to all auth endpoints and the "Generate Link" endpoint.