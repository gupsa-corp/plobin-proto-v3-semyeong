# Organization Member Management APIs

## Overview

Two APIs have been implemented for organization member management:

1. **Member Search API** - Search and check invitation status of users
2. **Bulk Member Invitation API** - Invite multiple members to organization (max 50)

## Authentication

Both APIs require user authentication through Laravel's session-based auth system.
User must be a member of the organization with appropriate permissions:
- Member search: Service Manager (level 200) or higher
- Member invitation: Organization Admin (level 300) or higher

## API Endpoints

### 1. Member Search API

**Endpoint:** `GET /api/organizations/{organizationId}/members/search`

**Purpose:** Search for users and check their invitation status for the organization.

**Parameters:**
- `query` (required): Search term for user name, email, or nickname
- `limit` (optional): Number of results to return (default: 10, max: 50)

**Example Request:**
```bash
curl -X GET "http://localhost:9100/api/organizations/1/members/search?query=admin&limit=10" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  --cookie-jar cookies.txt
```

**Response Format:**
```json
{
    "success": true,
    "data": {
        "users": [
            {
                "id": 1,
                "name": "admin",
                "email": "admin@example.com",
                "avatar": null,
                "status": "member|available|pending",
                "status_text": "이미 멤버|초대 가능|초대 대기 중",
                "permission": {
                    "level": 400,
                    "label": "조직 소유자",
                    "badge_color": "red"
                },
                "joined_at": "2025.09.06",
                "invited_at": "2025.09.06"
            }
        ],
        "total": 1,
        "query": "admin"
    }
}
```

**Status Values:**
- `available`: User can be invited
- `member`: User is already a member (accepted invitation)
- `pending`: User has pending invitation

### 2. Bulk Member Invitation API

**Endpoint:** `POST /api/organizations/{organizationId}/members/invite`

**Purpose:** Invite multiple users to the organization simultaneously.

**Request Body:**
```json
{
    "invitations": [
        {
            "email": "user@example.com",
            "permission_level": 200,
            "message": "Welcome to our organization!" // optional
        }
    ]
}
```

**Validation Rules:**
- `invitations`: Required array, min 1, max 50 members
- `invitations.*.email`: Required, must be valid email, must be unique within request
- `invitations.*.permission_level`: Required, must be valid permission level (0,100,150,200,250,300,350,400,450,500,550)
- `invitations.*.message`: Optional, max 500 characters

**Permission Levels:**
- `0`: 초대됨 (Invited)
- `100`: 사용자 (User)
- `150`: 고급 사용자 (Advanced User)
- `200`: 서비스 매니저 (Service Manager)
- `250`: 선임 서비스 매니저 (Senior Service Manager)
- `300`: 조직 관리자 (Organization Admin)
- `350`: 선임 조직 관리자 (Senior Organization Admin)
- `400`: 조직 소유자 (Organization Owner)
- `450`: 조직 창립자 (Organization Founder)
- `500`: 플랫폼 관리자 (Platform Admin)
- `550`: 최고 관리자 (Super Admin)

**Example Request:**
```bash
curl -X POST "http://localhost:9100/api/organizations/1/members/invite" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  --cookie-jar cookies.txt \
  -d '{
    "invitations": [
      {
        "email": "manager@example.com",
        "permission_level": 200,
        "message": "조직에 초대합니다!"
      },
      {
        "email": "user@example.com",
        "permission_level": 100
      }
    ]
  }'
```

**Response Format:**
```json
{
    "success": true,
    "message": "초대 처리가 완료되었습니다.",
    "data": {
        "total_processed": 3,
        "successful_count": 2,
        "failed_count": 1,
        "already_exists_count": 0,
        "results": {
            "successful": [
                {
                    "email": "manager@example.com",
                    "name": "매니저",
                    "permission": {
                        "level": 200,
                        "label": "서비스 매니저"
                    }
                }
            ],
            "failed": [
                {
                    "email": "unknown@example.com",
                    "reason": "등록되지 않은 사용자입니다. 먼저 회원가입을 진행해주세요."
                }
            ],
            "already_exists": [
                {
                    "email": "existing@example.com",
                    "status": "member",
                    "reason": "이미 조직 멤버입니다."
                }
            ]
        }
    }
}
```

## Permission Validation

The invitation API includes permission validation:
- Users cannot grant permissions higher than their own
- Only Organization Owner+ can grant Owner permissions
- Only Platform Admin+ can grant Platform Admin permissions

## Error Responses

**Authentication Error (401):**
```json
{
    "success": false,
    "message": "인증이 필요합니다."
}
```

**Permission Error (403):**
```json
{
    "success": false,
    "message": "멤버 관리 권한이 없습니다."
}
```

**Validation Error (422):**
```json
{
    "success": false,
    "message": "입력 데이터가 올바르지 않습니다.",
    "errors": {
        "invitations.0.email": ["The email field is required."],
        "invitations.0.permission_level": ["The selected permission level is invalid."]
    }
}
```

## Database Tables

**OrganizationMember Table:**
- `id`: Primary key
- `organization_id`: Foreign key to organizations table
- `user_id`: Foreign key to users table
- `permission_level`: Integer (0-550)
- `invitation_status`: Enum ('pending', 'accepted', 'declined')
- `invited_at`: Timestamp when invitation was sent
- `joined_at`: Timestamp when user accepted invitation
- `invited_by`: Foreign key to user who sent invitation
- `invitation_message`: Optional message with invitation

## Testing Status

✅ **Member Search API**: Tested and working
- Successfully searches users by name/email
- Correctly identifies member status (available/member/pending)
- Returns proper permission information for existing members

✅ **Bulk Invitation API**: Tested and working  
- Successfully processes multiple invitations simultaneously
- Proper validation and error handling
- Detailed response with success/failure breakdown
- Permission validation working correctly

✅ **Database Integration**: Tested and working
- OrganizationMember relationships properly established
- Data persistence verified
- Livewire component integration confirmed (shows correct member counts and stats)

## Integration Notes

The APIs are fully integrated with:
- Laravel authentication system
- OrganizationPermission enum for permission management
- Livewire components for real-time UI updates
- Proper validation and error handling
- Database transactions for data consistency