<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with realistic task management data.
     * Creates: 5 users, 2 projects, 100 tasks, 35 comments
     */
    public function run(): void
    {
        // Create 5 users with realistic names and emails
        $users = [
            User::create([
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@company.com',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Michael Chen',
                'email' => 'michael.chen@company.com',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Emma Rodriguez',
                'email' => 'emma.rodriguez@company.com',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'David Kim',
                'email' => 'david.kim@company.com',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@company.com',
                'password' => bcrypt('password'),
            ]),
        ];

        // Project 1: E-Commerce Platform Redesign (60 tasks)
        $project1 = Project::create([
            'name' => 'E-Commerce Platform Redesign',
            'description' => 'Complete overhaul of the online shopping experience with modern UI/UX, improved performance, and mobile-first approach.',
            'owner_id' => $users[0]->id,
            'status' => 'in_progress',
            'deadline' => now()->addDays(45),
        ]);

        // Project 2: Customer Portal Integration (40 tasks)
        $project2 = Project::create([
            'name' => 'Customer Portal Integration',
            'description' => 'Build a self-service customer portal with account management, order tracking, and support ticket system.',
            'owner_id' => $users[1]->id,
            'status' => 'in_progress',
            'deadline' => now()->addDays(60),
        ]);

        // E-Commerce Platform tasks (60 tasks)
        $ecomTasks = [
            // Design phase (15 tasks)
            ['title' => 'Create wireframes for homepage', 'description' => 'Design low-fidelity wireframes showing layout and content hierarchy', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 8, 'actual_hours' => 7],
            ['title' => 'Design product listing page mockups', 'description' => 'High-fidelity mockups with filters, sorting, and grid layout', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 14],
            ['title' => 'Create checkout flow designs', 'description' => 'Multi-step checkout with progress indicator and validation', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 16, 'actual_hours' => 15],
            ['title' => 'Design mobile responsive layouts', 'description' => 'Mobile-first designs for all key pages', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 20, 'actual_hours' => 12],
            ['title' => 'Create design system documentation', 'description' => 'Document colors, typography, spacing, and component library', 'status' => 'in_progress', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 5],
            ['title' => 'Design user account dashboard', 'description' => 'Order history, wishlist, and saved addresses interface', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Create product detail page design', 'description' => 'Image gallery, specifications, reviews, and related products', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Design shopping cart interface', 'description' => 'Item management, quantity updates, and coupon code entry', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Create loading and error states', 'description' => 'Design skeleton screens and error messages', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Design search results page', 'description' => 'Auto-complete suggestions and search filters', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Create promotional banner designs', 'description' => 'Hero banners and sale announcement templates', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Design wishlist functionality', 'description' => 'Add to wishlist, share, and move to cart features', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 5, 'actual_hours' => 0],
            ['title' => 'Create email notification templates', 'description' => 'Order confirmation, shipping, and promotional emails', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Design comparison table layout', 'description' => 'Side-by-side product comparison interface', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Create accessibility audit report', 'description' => 'Review designs for WCAG 2.1 compliance', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 6, 'actual_hours' => 0],

            // Frontend development (25 tasks)
            ['title' => 'Set up React project structure', 'description' => 'Initialize project with Vite, TypeScript, and Tailwind CSS', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 4, 'actual_hours' => 5],
            ['title' => 'Implement navigation component', 'description' => 'Header with search, cart icon, and user menu', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 8, 'actual_hours' => 9],
            ['title' => 'Build product card component', 'description' => 'Reusable card with image, price, rating, and quick actions', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 6, 'actual_hours' => 6],
            ['title' => 'Create homepage layout', 'description' => 'Hero section, featured products, and category highlights', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 7],
            ['title' => 'Implement product listing page', 'description' => 'Grid layout with filters, sorting, and pagination', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 16, 'actual_hours' => 10],
            ['title' => 'Build filter sidebar component', 'description' => 'Multi-select filters with price range slider', 'status' => 'in_progress', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 6],
            ['title' => 'Create product detail page', 'description' => 'Image zoom, size selector, and add to cart', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 14, 'actual_hours' => 0],
            ['title' => 'Implement shopping cart page', 'description' => 'Item list with quantity controls and price summary', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Build checkout flow', 'description' => 'Multi-step form with validation and payment integration', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 20, 'actual_hours' => 0],
            ['title' => 'Create user authentication pages', 'description' => 'Login, register, and password reset forms', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 0],
            ['title' => 'Implement search functionality', 'description' => 'Auto-complete with debounced API calls', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Build user dashboard', 'description' => 'Order history, saved addresses, and account settings', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 16, 'actual_hours' => 0],
            ['title' => 'Create wishlist page', 'description' => 'Saved items with add to cart and remove options', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Implement product reviews section', 'description' => 'Rating display, review list, and submit review form', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Add loading skeletons', 'description' => 'Skeleton screens for all async data loading', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Build comparison page', 'description' => 'Side-by-side product comparison table', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Implement responsive navigation', 'description' => 'Mobile hamburger menu with slide-out drawer', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Create error boundary components', 'description' => 'Graceful error handling with fallback UI', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Add toast notifications', 'description' => 'Success, error, and info message system', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Implement infinite scroll', 'description' => 'Lazy loading for product listing pages', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Build image lightbox', 'description' => 'Full-screen image viewer with zoom and navigation', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 5, 'actual_hours' => 0],
            ['title' => 'Create breadcrumb component', 'description' => 'Navigation breadcrumbs for all pages', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 3, 'actual_hours' => 0],
            ['title' => 'Implement analytics tracking', 'description' => 'Google Analytics and conversion tracking', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Add SEO meta tags', 'description' => 'Dynamic meta tags for all pages', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Optimize bundle size', 'description' => 'Code splitting and lazy loading optimization', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],

            // Backend development (20 tasks)
            ['title' => 'Design database schema', 'description' => 'Create ERD for products, orders, and users', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 6, 'actual_hours' => 8],
            ['title' => 'Set up API project structure', 'description' => 'Initialize Node.js with Express and TypeScript', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 4, 'actual_hours' => 4],
            ['title' => 'Implement product API endpoints', 'description' => 'CRUD operations for product management', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 14],
            ['title' => 'Create authentication system', 'description' => 'JWT-based auth with refresh tokens', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 16, 'actual_hours' => 18],
            ['title' => 'Build order management endpoints', 'description' => 'Create, update, and track orders', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 14, 'actual_hours' => 8],
            ['title' => 'Implement payment integration', 'description' => 'Stripe payment processing with webhooks', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 20, 'actual_hours' => 12],
            ['title' => 'Create search API with Elasticsearch', 'description' => 'Full-text search with faceted filters', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 16, 'actual_hours' => 0],
            ['title' => 'Build inventory management system', 'description' => 'Stock tracking and low inventory alerts', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 0],
            ['title' => 'Implement email service', 'description' => 'Order confirmation and shipping notification emails', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Create admin dashboard API', 'description' => 'Analytics and reporting endpoints', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Build product review endpoints', 'description' => 'Submit, approve, and display reviews', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Implement wishlist API', 'description' => 'Add, remove, and retrieve wishlist items', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Create coupon management system', 'description' => 'Discount codes with validation and usage tracking', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Set up Redis caching', 'description' => 'Cache product data and session management', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Implement rate limiting', 'description' => 'Protect APIs from abuse and DDoS', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Build shipping calculator', 'description' => 'Integration with shipping providers for rates', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 12, 'actual_hours' => 0],
            ['title' => 'Create audit logging system', 'description' => 'Track all administrative actions', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Implement API documentation', 'description' => 'OpenAPI/Swagger documentation', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Set up monitoring and logging', 'description' => 'Application performance monitoring with DataDog', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Write API integration tests', 'description' => 'Comprehensive test suite for all endpoints', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 16, 'actual_hours' => 0],
        ];

        // Customer Portal tasks (40 tasks)
        $portalTasks = [
            // Planning & Design (10 tasks)
            ['title' => 'Conduct stakeholder interviews', 'description' => 'Gather requirements from customer service and sales teams', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 8, 'actual_hours' => 10],
            ['title' => 'Create user journey maps', 'description' => 'Map customer interactions and pain points', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 6, 'actual_hours' => 7],
            ['title' => 'Design portal architecture', 'description' => 'Define microservices and data flow', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 15],
            ['title' => 'Create UI mockups for dashboard', 'description' => 'Main dashboard with key metrics and quick actions', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 10, 'actual_hours' => 11],
            ['title' => 'Design account settings interface', 'description' => 'Profile, preferences, and notification settings', 'status' => 'in_progress', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 4],
            ['title' => 'Create support ticket UI designs', 'description' => 'Submit, view, and track support requests', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 10, 'actual_hours' => 5],
            ['title' => 'Design order tracking interface', 'description' => 'Timeline view with shipment updates', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Create document upload designs', 'description' => 'Drag-and-drop file upload with preview', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Design notification center', 'description' => 'In-app notifications with read/unread status', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Create mobile app wireframes', 'description' => 'iOS and Android portal app designs', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 12, 'actual_hours' => 0],

            // Frontend Development (15 tasks)
            ['title' => 'Set up Next.js project', 'description' => 'Initialize with TypeScript, Tailwind, and auth', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 4, 'actual_hours' => 5],
            ['title' => 'Implement authentication flow', 'description' => 'Login, registration, and password reset', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 14],
            ['title' => 'Build main dashboard page', 'description' => 'Overview cards with recent activity', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 10, 'actual_hours' => 6],
            ['title' => 'Create order history component', 'description' => 'Paginated table with search and filters', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 12, 'actual_hours' => 7],
            ['title' => 'Implement order detail view', 'description' => 'Detailed order info with tracking', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Build support ticket system', 'description' => 'Create, view, and respond to tickets', 'status' => 'todo', 'priority' => 'high', 'estimated_hours' => 16, 'actual_hours' => 0],
            ['title' => 'Create account settings page', 'description' => 'Profile editing and preference management', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Implement document manager', 'description' => 'Upload, view, and organize documents', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 12, 'actual_hours' => 0],
            ['title' => 'Build notification system', 'description' => 'Real-time notifications with WebSocket', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Create billing history page', 'description' => 'Invoice list with download option', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Implement chat widget', 'description' => 'Live chat support integration', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 14, 'actual_hours' => 0],
            ['title' => 'Build FAQ section', 'description' => 'Searchable knowledge base', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Create onboarding tutorial', 'description' => 'Interactive guide for new users', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Implement theme customization', 'description' => 'Light/dark mode toggle', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Add accessibility features', 'description' => 'Screen reader support and keyboard navigation', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],

            // Backend Development (15 tasks)
            ['title' => 'Set up Laravel API project', 'description' => 'Initialize with Sanctum authentication', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 4, 'actual_hours' => 4],
            ['title' => 'Create customer API endpoints', 'description' => 'Profile management and preferences', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 10, 'actual_hours' => 12],
            ['title' => 'Implement order API', 'description' => 'Fetch order history and details', 'status' => 'done', 'priority' => 'high', 'estimated_hours' => 8, 'actual_hours' => 9],
            ['title' => 'Build support ticket API', 'description' => 'Create, update, and retrieve tickets', 'status' => 'in_progress', 'priority' => 'high', 'estimated_hours' => 14, 'actual_hours' => 8],
            ['title' => 'Create notification service', 'description' => 'Push notifications and email alerts', 'status' => 'in_progress', 'priority' => 'medium', 'estimated_hours' => 12, 'actual_hours' => 6],
            ['title' => 'Implement document storage', 'description' => 'S3 integration for file uploads', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Build activity tracking', 'description' => 'Log user actions and generate timeline', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Create billing API', 'description' => 'Invoice generation and payment history', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 12, 'actual_hours' => 0],
            ['title' => 'Implement search functionality', 'description' => 'Full-text search across orders and tickets', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 10, 'actual_hours' => 0],
            ['title' => 'Build reporting system', 'description' => 'Generate customer usage reports', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 14, 'actual_hours' => 0],
            ['title' => 'Create webhook system', 'description' => 'Event notifications for external systems', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Implement data export', 'description' => 'Export customer data in CSV/PDF format', 'status' => 'todo', 'priority' => 'low', 'estimated_hours' => 6, 'actual_hours' => 0],
            ['title' => 'Set up background jobs', 'description' => 'Queue system for async processing', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 8, 'actual_hours' => 0],
            ['title' => 'Create API rate limiting', 'description' => 'Throttle requests per customer', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 4, 'actual_hours' => 0],
            ['title' => 'Write integration tests', 'description' => 'Test suite for all API endpoints', 'status' => 'todo', 'priority' => 'medium', 'estimated_hours' => 16, 'actual_hours' => 0],
        ];

        // Create tasks for project 1 with varied assignees
        foreach ($ecomTasks as $index => $taskData) {
            $task = Task::create([
                'project_id' => $project1->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
                'priority' => $taskData['priority'],
                'estimated_hours' => $taskData['estimated_hours'],
                'actual_hours' => $taskData['actual_hours'],
                'assigned_to' => $users[$index % 5]->id, // Distribute among all 5 users
            ]);
        }

        // Create tasks for project 2
        foreach ($portalTasks as $index => $taskData) {
            $task = Task::create([
                'project_id' => $project2->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status' => $taskData['status'],
                'priority' => $taskData['priority'],
                'estimated_hours' => $taskData['estimated_hours'],
                'actual_hours' => $taskData['actual_hours'],
                'assigned_to' => $users[$index % 5]->id, // Distribute among all 5 users
            ]);
        }

        // Create 35 realistic comments distributed across tasks
        $allTasks = Task::all();
        $comments = [
            // Technical discussions
            ['comment' => 'I\'ve reviewed the Figma designs and they look great. One concern: the mobile breakpoint should be 768px instead of 640px to match our design system.', 'user' => 0],
            ['comment' => 'Agreed. I\'ll update the Tailwind config. Also noticed we need to add hover states for the product cards.', 'user' => 2],
            ['comment' => 'The API response time is averaging 320ms. We should implement Redis caching to get it under 100ms.', 'user' => 1],
            ['comment' => 'Good catch. I\'ll set up Redis this week and configure cache invalidation for product updates.', 'user' => 3],
            ['comment' => 'The checkout flow needs better error handling. Users aren\'t seeing validation messages when payment fails.', 'user' => 4],

            // Progress updates
            ['comment' => 'Homepage is 80% complete. Just need to add the testimonials section and optimize images.', 'user' => 2],
            ['comment' => 'Authentication is working great! All tests passing. Moving on to the dashboard next.', 'user' => 1],
            ['comment' => 'Finished the database migrations. All foreign keys and indexes are in place.', 'user' => 3],
            ['comment' => 'Product listing page is responsive now. Tested on iPhone 12, Pixel 5, and iPad Air.', 'user' => 0],
            ['comment' => 'Stripe integration is complete. Successfully tested with test cards and webhooks are working.', 'user' => 4],

            // Questions and blockers
            ['comment' => 'Quick question: should we support guest checkout or require account creation?', 'user' => 2],
            ['comment' => 'Let\'s support guest checkout. We can prompt them to create an account after order confirmation.', 'user' => 0],
            ['comment' => 'Blocked: Need API credentials for the shipping calculator. @Sarah can you provide access?', 'user' => 3],
            ['comment' => 'I\'ll request access from the vendor. Should have credentials by tomorrow.', 'user' => 0],
            ['comment' => 'Do we have a content strategy for the FAQ section? Need actual questions and answers.', 'user' => 4],

            // Code reviews
            ['comment' => 'Reviewed your PR. The product filter logic looks solid, but we should extract it into a custom hook for reusability.', 'user' => 1],
            ['comment' => 'Great suggestion! I\'ll refactor it into useProductFilters() hook.', 'user' => 2],
            ['comment' => 'The API endpoints are well-structured. Consider adding rate limiting to prevent abuse.', 'user' => 0],
            ['comment' => 'Left a few comments on your PR. Mostly small nitpicks about TypeScript types and error handling.', 'user' => 3],
            ['comment' => 'Thanks for the thorough review! I\'ve addressed all comments and force-pushed.', 'user' => 1],

            // Design feedback
            ['comment' => 'The color contrast on the CTA buttons doesn\'t meet WCAG AA standards. Need to darken the text or lighten the background.', 'user' => 4],
            ['comment' => 'Good eye! I\'ll adjust the brand colors in the design system to ensure accessibility.', 'user' => 2],
            ['comment' => 'Love the micro-interactions on the product cards! The hover effect really makes them pop.', 'user' => 0],
            ['comment' => 'The spacing between sections feels inconsistent. Let\'s standardize on 48px for desktop and 32px for mobile.', 'user' => 1],

            // Testing and bugs
            ['comment' => 'Found a bug: cart total doesn\'t update when quantity changes. It only refreshes on page reload.', 'user' => 3],
            ['comment' => 'I can reproduce this. It\'s a state management issue. Will fix today.', 'user' => 2],
            ['comment' => 'All unit tests are passing, but we should add more integration tests for the checkout flow.', 'user' => 1],
            ['comment' => 'Search functionality is breaking with special characters. Need to sanitize input before sending to Elasticsearch.', 'user' => 4],
            ['comment' => 'The mobile menu doesn\'t close when clicking outside. Adding onClickOutside handler.', 'user' => 0],

            // Performance and optimization
            ['comment' => 'Bundle size is 890KB. We should implement code splitting for the checkout and dashboard routes.', 'user' => 1],
            ['comment' => 'I\'ll set up lazy loading this afternoon. Should get it under 300KB for the initial load.', 'user' => 2],
            ['comment' => 'Product images are loading slowly. Recommend using next/image with proper sizing and WebP format.', 'user' => 3],
            ['comment' => 'The database queries for product listings are generating N+1 problems. Need to add eager loading.', 'user' => 1],

            // Documentation
            ['comment' => 'Added comprehensive JSDoc comments for all utility functions. Makes the codebase much easier to navigate.', 'user' => 2],
            ['comment' => 'Updated the API documentation with all the new endpoints. Swagger UI is looking great!', 'user' => 4],
        ];

        // Distribute comments across tasks (focus on in-progress and done tasks)
        $commentedTasks = $allTasks->whereIn('status', ['done', 'in_progress'])->shuffle();

        foreach ($comments as $index => $commentData) {
            if ($index < $commentedTasks->count()) {
                TaskComment::create([
                    'task_id' => $commentedTasks[$index]->id,
                    'user_id' => $users[$commentData['user']]->id,
                    'comment' => $commentData['comment'],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Created: 5 users, 2 projects, 100 tasks, 35 comments');
        $this->command->info('Users: sarah.johnson@company.com, michael.chen@company.com, emma.rodriguez@company.com, david.kim@company.com, lisa.anderson@company.com');
        $this->command->info('Password for all users: password');
    }
}
