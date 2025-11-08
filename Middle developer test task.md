# Test Task for Middle Full-Stack Developer

## Overview
This test task is designed to evaluate your skills in full-stack development with PHP/Laravel, Vue.js/Nuxt, MySQL, and related technologies. Please complete all tasks and follow the requirements carefully.

**Time estimate:** flexible within 1 - 1.5 weeks

## Project Description
Create a simple **Task Management API** with a modern frontend interface. The application should allow users to manage projects and their associated tasks.

---

## Requirements

### 1. Backend (PHP/Laravel)

#### 1.1 Database Design (Advanced SQL Level)
Create a MySQL database with the following structure:

- **users** table (id, name, email, password, role, created_at, updated_at)
- **projects** table (id, name, description, owner_id, status, deadline, created_at, updated_at)
- **tasks** table (id, project_id, title, description, assigned_to, status, priority, estimated_hours, actual_hours, created_at, updated_at)
- **task_comments** table (id, task_id, user_id, comment, created_at, updated_at)

**SQL Tasks:**
- Create migrations with proper foreign keys, indexes, and constraints
- Write at least 2 complex SQL queries using:
  - JOINs across multiple tables
  - Subqueries or CTEs (Common Table Expressions) | Optional, but preferred
  - Aggregation functions (COUNT, SUM, AVG)
  - GROUP BY and HAVING clauses
  
**Example queries to implement:**
1. Get all projects with task statistics (total tasks, completed tasks, total estimated vs actual hours)
2. Find overdue tasks with project and assignee information
3. Calculate productivity metrics per user (tasks completed, average completion time)

#### 1.2 REST API Development (Advanced Code Structure)
Implement a RESTful API with the following endpoints:

**Projects:**
- `GET /api/projects` - List all projects with filtering and pagination
- `GET /api/projects/{id}` - Get project details with related tasks
- `POST /api/projects` - Create a new project
- `PUT /api/projects/{id}` - Update project
- `DELETE /api/projects/{id}` - Delete project

**Tasks:**
- `GET /api/tasks` - List all tasks with advanced filtering (status, priority, assignee, project)
- `GET /api/tasks/{id}` - Get task details with comments
- `POST /api/tasks` - Create a new task
- `PUT /api/tasks/{id}` - Update task
- `PATCH /api/tasks/{id}/status` - Update task status
- `DELETE /api/tasks/{id}` - Delete task

**Task Comments:**
- `GET /api/tasks/{id}/comments` - Get task comments
- `POST /api/tasks/{id}/comments` - Add comment to task

**Statistics:**
- `GET /api/statistics/dashboard` - Get dashboard statistics (complex aggregations)

#### 1.3 Code Structure & OOP Requirements
Your code must demonstrate:

- **SOLID Principles:**
  - Single Responsibility: Separate concerns (Controllers, Services, Models)
  - Open/Closed: Use interfaces and dependency injection
  - Liskov Substitution: Proper inheritance usage
  - Interface Segregation: Create focused interfaces
  - Dependency Inversion: Depend on abstractions

- **Design Patterns:**
  - Service Layer for business logic
  - Factory Pattern (if applicable)
  - Observer Pattern (for events, e.g., task status changes)

- **Clean Code (KISS):**
  - Meaningful variable and method names
  - Small, focused functions
  - Proper code comments where necessary
  - Consistent code formatting

**Required Structure:**
```
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   ├── Resources/
│   └── Middleware/
├── Services/
│   ├── ProjectService.php
│   └── TaskService.php
├── Models/
├── Events/
├── Listeners/
└── Exceptions/
```

#### 1.4 Additional Backend Requirements
- Implement **API authentication** (Laravel Sanctum or Passport)
- Add **validation** using Form Requests
- Use **API Resources** for consistent response formatting
- Implement **error handling** with custom exceptions
- Add **database seeders** with realistic test data
- Create **Eloquent relationships** properly (hasMany, belongsTo, etc.)
- Implement **soft deletes** where appropriate

#### 1.5 Unit Tests
Write unit tests for:
- At least 1 Service class
- At least 1 API endpoints

Use PHPUnit with proper test structure:
```php
- Feature tests for API endpoints
- Unit tests for services
- Use factories for test data
- Test edge cases and error scenarios
```

---

### 2. Frontend (Vue.js/Nuxt) - Advanced JS Level

#### 2.1 Application Structure
Create a Nuxt 3 application with the following pages:

- **Dashboard** (`/`) - Overview with statistics
- **Projects List** (`/projects`) - List all projects with filters
- **Project Details** (`/projects/:id`) - Show project with tasks
- **Task Board** (`/tasks`) - Kanban-style task board
- **Task Details** (`/tasks/:id`) - Detailed task view with comments

#### 2.2 Advanced JavaScript/Vue.js Requirements

**Composables & Composition API:**
```javascript
// Create reusable composables
composables/
├── useApi.js
├── useAuth.js
├── useFilters.js
├── useProjects.js
└── useTasks.js
```

**State Management:**
- Use **Pinia** for state management
- Create stores for:
  - Authentication
  - Projects
  - Tasks
  - UI state (loading, errors, notifications)

**Advanced Features:**
1. **Real-time filtering and search** (debounced)
2. **Drag-and-drop** task management (Kanban board)
3. **Optimistic UI updates** for better UX
4. **Form validation** with VeeValidate or custom solution
5. **Infinite scroll or pagination** for lists
6. **Date picker** for deadlines
7. **Rich text editor** for descriptions (optional)

**Component Structure:**
```
components/
├── common/
│   ├── BaseButton.vue
│   ├── BaseInput.vue
│   ├── BaseModal.vue
│   └── LoadingSpinner.vue
├── projects/
│   ├── ProjectCard.vue
│   ├── ProjectForm.vue
│   └── ProjectFilters.vue
├── tasks/
│   ├── TaskCard.vue
│   ├── TaskForm.vue
│   ├── TaskBoard.vue
│   └── TaskComments.vue
└── layout/
    ├── Header.vue
    ├── Sidebar.vue
    └── Notification.vue
```

#### 2.3 JavaScript Code Quality
Your JavaScript code must demonstrate:

- **ES6+ features:** async/await, destructuring, spread operator, arrow functions
- **Proper error handling:** try-catch blocks, error boundaries
- **Type safety:** Can be skipped for this task, can be used only vanilla JS
- **Performance optimization:**
  - Computed properties for derived state
  - Watchers with proper cleanup
  - Component lazy loading
  - Virtual scrolling for large lists (bonus)
- **Clean code practices:**
  - Single responsibility per component
  - Reusable utility functions
  - Consistent naming conventions

#### 2.4 Styling (Tailwind or Bootstrap)
- Use either **Tailwind CSS** or **Bootstrap Vue**
- Responsive design (mobile, tablet, desktop)
- Consistent color scheme and spacing
- Loading states and skeleton screens
- Toast notifications for user feedback

---

### 3. Docker Configuration

Create a Docker setup with:

```yaml
# docker-compose.yml structure
services:
  - app (PHP 8.2+, Laravel)
  - nginx (Web server)
  - mysql (Database)
  - node (For Nuxt dev server)
  - phpMyAdmin (Optional, for convenience in dev mode)
```

**Requirements:**
- Include Dockerfile for PHP application
- Volume mounting for development
- Environment variable configuration
- One-command setup: `docker-compose up -d`
- Include setup instructions in README

---

### 4. Git Workflow

**Required Git practices:**

1. **Branch Strategy:**
   - Create `develop` branch from `main`
   - Create feature branches: `feature/backend-api`, `feature/frontend-dashboard`, etc.
   - Create a `docker-setup` branch

2. **Commit Messages:**
   - Use conventional commits format
   - Examples:
     - `feat: add project CRUD endpoints`
     - `fix: correct task status validation`
     - `refactor: extract task service logic`
     - `test: add unit tests for ProjectService`
     - `docs: update API documentation`

3. **Required commits:**
   - Minimum 10 meaningful commits
   - Show progressive development
   - Demonstrate proper Git hygiene (no secrets committed, proper .gitignore)
---

## Evaluation Criteria

### Backend (40 points)
- [ ] Database design and migrations (5 points)
- [ ] Advanced SQL queries (5 points)
- [ ] API implementation (10 points)
- [ ] Code structure and SOLID principles (10 points)
- [ ] Unit tests (5 points)
- [ ] Error handling and validation (5 points)

### Frontend (30 points)
- [ ] Component structure and reusability (8 points)
- [ ] Advanced JavaScript/Vue.js features (8 points)
- [ ] State management (5 points)
- [ ] UI/UX and responsiveness (5 points)
- [ ] Code quality and organization (4 points)

### DevOps & Tools (20 points)
- [ ] Docker configuration (10 points)
- [ ] Git workflow and commit quality (10 points)

### Documentation & Best Practices (10 points)
- [ ] README quality (4 points)
- [ ] API documentation (3 points)
- [ ] Code comments and clarity (3 points)

**Bonus Points (up to +10):**
- Automated code formatting (PHP-CS-Fixer, ESLint, Prettier)
- Additional features (file uploads, etc.)
- Performance optimizations
---

## Submission Guidelines

1. Create a **private repository** (or public if you prefer)
2. Add repository URL to your application
3. Ensure `docker-compose up` works from scratch
4. Include `.env.example` file
5. Ensure README has complete setup instructions
6. Submit within **7-10 days** from receiving the task

---

## Questions?

If you have any questions about the requirements, please reach out to 'cv@elizings.com' with the subject 'Name Surname: Completed fullstack middle technical task'. We're here to help clarify!

---

## Tips for Success

1. **Start with planning** - sketch out your database and API structure first
2. **Follow Laravel/Vue.js best practices** - don't reinvent the wheel
3. **Commit often** - show your development process
4. **Focus on code quality over features** - we value clean code
5. **Document your decisions** - explain why you chose certain approaches
6. **Keep it simple** - KISS principle applies to the entire task

---

Good luck! We're excited to see your work. 🚀