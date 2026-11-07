
# Secure Document Workflow System - Sanitized Demo

Sanitized version of a production workflow system. All real data, ministry names, and user data removed.

This demo shows the core security design:
- Role-Based Access Control (RBAC)
- Audit Logging (who / when / what)
- Separation of Duties - No user can approve their own document
- Secure lifecycle: Draft -> Submitted -> Reviewed -> Approved -> Archived

**Stack:** PHP 8 / Laravel 9 / MySQL 8

**Original Impact (Production):**
- 8 departments, 120+ users
- Processing time: 7 days -> 2 days
- 14 security / logic bugs fixed before deployment

## Security Design
1. Threat Model: Insider misuse, privilege escalation, tampered records
2. Controls: RBAC, audit logs, encrypted storage, input validation
3. Audit: Every create, view, approve, download is logged

## How It Works
1. User creates a document - status = Draft
2. User submits - status = Submitted
3. Reviewer reviews - status = Reviewed
4. Approver approves - status = Approved
5. System logs every step in audit_logs table

## Why This Matters For Product Security
This is product security in practice: threat modeling a real workflow, enforcing least privilege with RBAC, and making every action traceable so tampering can be detected.
