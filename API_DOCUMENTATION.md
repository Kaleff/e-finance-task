# API Documentation

Base URL: `http://localhost:8000/api`

## Authentication

All endpoints except login and register require a Bearer token in the Authorization header:
```
Authorization: Bearer {token}
```

### Auth Endpoints

#### Register
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "token": "1|abc123..."
}
```

#### Login
```http
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "token": "1|abc123..."
}
```

#### Logout
```http
POST /logout
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Logged out successfully"
}
```

#### Get Current User
```http
GET /user
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com"
}
```

---

## Projects

### List Projects
```http
GET /projects?status={status}&owner_id={id}&deadline_passed={bool}&page={page}&per_page={perPage}
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (optional): `planned`, `in_progress`, `completed`, `archived`
- `owner_id` (optional): Filter by project owner
- `deadline_passed` (optional): `true` or `false`
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 10)

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "E-Commerce Platform",
      "description": "Complete overhaul...",
      "status": "in_progress",
      "owner_id": 1,
      "deadline": "2025-12-31",
      "tasks_count": 60,
      "completed_tasks_count": 20,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-15T00:00:00.000000Z"
    }
  ],
  "current_page": 1,
  "last_page": 3,
  "per_page": 10,
  "total": 25
}
```

### Get Project by ID
```http
GET /projects/{id}?page={page}&per_page={perPage}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "id": 1,
  "name": "E-Commerce Platform",
  "description": "Complete overhaul...",
  "status": "in_progress",
  "owner_id": 1,
  "deadline": "2025-12-31",
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-15T00:00:00.000000Z",
  "tasks": [...],
  "stats": {
    "total": 60,
    "todo": 25,
    "in_progress": 15,
    "completed": 20
  },
  "tasks_pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 20,
    "total": 60,
    "from": 1,
    "to": 20
  }
}
```

### Create Project
```http
POST /projects
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New Project",
  "description": "Project description",
  "status": "planned",
  "owner_id": 1,
  "deadline": "2025-12-31"
}
```

**Response (201):**
```json
{
  "id": 2,
  "name": "New Project",
  "description": "Project description",
  "status": "planned",
  "owner_id": 1,
  "deadline": "2025-12-31"
}
```

### Update Project
```http
PUT /projects/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Project Name",
  "status": "in_progress"
}
```

**Response (200):**
```json
{
  "id": 1,
  "name": "Updated Project Name",
  "status": "in_progress",
  ...
}
```

### Delete Project
```http
DELETE /projects/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Project deleted successfully"
}
```

---

## Tasks

### List Tasks
```http
GET /tasks?project_id={id}&status={status}&priority={priority}&assigned_to={userId}&page={page}&per_page={perPage}
Authorization: Bearer {token}
```

**Query Parameters:**
- `project_id` (optional): Filter by project
- `status` (optional): `todo`, `in_progress`, `done`, `cancelled`
- `priority` (optional): `low`, `medium`, `high`
- `assigned_to` (optional): Filter by assigned user
- `page` (optional): Page number
- `per_page` (optional): Items per page

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "project_id": 1,
      "title": "Create wireframes",
      "description": "Design low-fidelity wireframes...",
      "status": "done",
      "priority": "high",
      "assigned_to": 2,
      "estimated_hours": 8,
      "actual_hours": 7,
      "comments_count": 3,
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  ],
  "current_page": 1,
  "per_page": 20,
  "total": 100
}
```

### Get Task by ID
```http
GET /tasks/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "id": 1,
  "project_id": 1,
  "title": "Create wireframes",
  "description": "Design low-fidelity wireframes...",
  "status": "done",
  "priority": "high",
  "assigned_to": 2,
  "estimated_hours": 8,
  "actual_hours": 7,
  "created_at": "2025-01-01T00:00:00.000000Z",
  "updated_at": "2025-01-05T00:00:00.000000Z"
}
```

### Create Task
```http
POST /tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "project_id": 1,
  "title": "New Task",
  "description": "Task description",
  "status": "todo",
  "priority": "medium",
  "assigned_to": 2,
  "estimated_hours": 10
}
```

**Response (201):**
```json
{
  "id": 101,
  "project_id": 1,
  "title": "New Task",
  ...
}
```

### Update Task
```http
PUT /tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Task Title",
  "status": "in_progress",
  "actual_hours": 5
}
```

**Response (200):**
```json
{
  "id": 1,
  "title": "Updated Task Title",
  "status": "in_progress",
  ...
}
```

### Update Task Status
```http
PATCH /tasks/{id}/status
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "done"
}
```

**Response (200):**
```json
{
  "id": 1,
  "status": "done",
  ...
}
```

### Update Task Priority
```http
PATCH /tasks/{id}/priority
Authorization: Bearer {token}
Content-Type: application/json

{
  "priority": "high"
}
```

### Update Task Assignee
```http
PATCH /tasks/{id}/assignee
Authorization: Bearer {token}
Content-Type: application/json

{
  "assigned_to": 3
}
```

### Delete Task
```http
DELETE /tasks/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Task deleted successfully"
}
```

---

## Task Comments

### List Comments for Task
```http
GET /tasks/{taskId}/comments
Authorization: Bearer {token}
```

**Response (200):**
```json
[
  {
    "id": 1,
    "task_id": 1,
    "user_id": 2,
    "comment": "Great progress on this!",
    "user": {
      "id": 2,
      "name": "Jane Doe",
      "email": "jane@example.com"
    },
    "created_at": "2025-01-10T00:00:00.000000Z"
  }
]
```

### Create Comment
```http
POST /tasks/{taskId}/comments
Authorization: Bearer {token}
Content-Type: application/json

{
  "comment": "This looks great!"
}
```

**Response (201):**
```json
{
  "id": 2,
  "task_id": 1,
  "user_id": 1,
  "comment": "This looks great!",
  "created_at": "2025-01-15T00:00:00.000000Z"
}
```

### Update Comment
```http
PUT /tasks/comments/{commentId}
Authorization: Bearer {token}
Content-Type: application/json

{
  "comment": "Updated comment text"
}
```

### Delete Comment
```http
DELETE /tasks/comments/{commentId}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Comment deleted successfully"
}
```

---

## Users

### List Users
```http
GET /users
Authorization: Bearer {token}
```

**Response (200):**
```json
[
  {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  {
    "id": 2,
    "name": "Jane Smith",
    "email": "jane@example.com"
  }
]
```

---

## Statistics

### Get General Stats
```http
GET /stats
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "total_projects": 2,
  "total_tasks": 100,
  "completed_tasks": 25,
  "completion_percentage": 25.0
}
```

### Get Project Overview
```http
GET /stats/project-overview
Authorization: Bearer {token}
```

**Response (200):**
```json
[
  {
    "project_name": "E-Commerce Platform",
    "owner_name": "Sarah Johnson",
    "total_tasks": 60,
    "completed_tasks": 20,
    "completion_rate": 33.3,
    "team_members": 5,
    "comment_count": 22
  },
  {
    "project_name": "Customer Portal",
    "owner_name": "Michael Chen",
    "total_tasks": 40,
    "completed_tasks": 5,
    "completion_rate": 12.5,
    "team_members": 4,
    "comment_count": 13
  }
]
```

---

## Status Codes

- **200 OK**: Request successful
- **201 Created**: Resource created successfully
- **400 Bad Request**: Invalid request data
- **401 Unauthorized**: Missing or invalid authentication token
- **403 Forbidden**: Insufficient permissions
- **404 Not Found**: Resource not found
- **422 Unprocessable Entity**: Validation errors
- **500 Internal Server Error**: Server error

## Error Response Format

```json
{
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```
