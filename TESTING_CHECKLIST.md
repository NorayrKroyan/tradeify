# Acceptance Testing Checklist

## Authentication
- Registration works.
- Verification email is required.
- Password reset works.
- Administrator has two-factor authentication enabled.

## Company verification
- Unverified user cannot access trade-post creation.
- User can upload only PDF/JPG/JPEG/PNG documents.
- Files larger than 10 MB are rejected.
- Verification documents are not available under `/storage`.
- Company registration number is unique per country.
- Administrator can approve, reject, and suspend.
- Approval assigns correct roles.
- Rejection and suspension remove publishing access.
- Every decision creates a review and audit log.

## Trade posts
- Approved buyer can create buy posts.
- Approved seller can create sell posts.
- Approved company with both activities can create both.
- New post is pending, not public.
- Administrator can publish/reject/archive.
- Only published and unexpired posts appear publicly.
- User can see own pending/rejected posts.
- Images validate and display.

## Security
- User cannot download another company's verification documents.
- Non-admin cannot enter admin routes.
- User cannot edit another user's post.
- Suspended user cannot create posts.
- Deleted or disabled categories cannot be selected.
- Rate limiting is enabled on login, registration, upload, and post creation.
