<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// ==================== PUBLIC ROUTES ====================

// Homepage
$routes->get('/', 'Home::index');

// Auth Routes
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/do-login', 'Auth::doLogin');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/do-register', 'Auth::doRegister');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/forgot-password', 'Auth::forgotPassword');
$routes->post('/auth/do-forgot-password', 'Auth::doForgotPassword');
$routes->get('/auth/reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('/auth/do-reset-password', 'Auth::doResetPassword');

// Theme Toggle
$routes->post('/theme/toggle', 'Theme::toggle');

// Public Member Routes
$routes->get('/members/directory', 'MemberController::directory');
$routes->get('/members/view/(:num)', 'MemberController::view/$1');
$routes->get('/members/profile', 'MemberController::profile');
$routes->post('/members/update-profile', 'MemberController::updateProfile');
$routes->post('/members/change-password', 'MemberController::changePassword');
$routes->post('/members/react/(:num)', 'MemberController::react/$1');
// Birthday Routes
$routes->get('/birthday/calendar', 'Birthday::calendar');
$routes->get('/birthday/wall', 'Birthday::wall');
$routes->post('/birthday/submit-wish', 'Birthday::submitWish');
$routes->post('/birthday/get-month-data', 'Birthday::getMonthData');
$routes->get('/birthday/statistics', 'Birthday::statistics');

// Event Routes (Public)
$routes->get('/events', 'Events::index');
$routes->get('/events/(:any)', 'Events::view/$1');
$routes->post('/events/rsvp/(:num)', 'Events::rsvp/$1');

// News Routes (Public)
$routes->get('/news', 'News::index');
$routes->get('/news/(:any)', 'News::view/$1');

// Gallery Routes (Public)
$routes->get('/gallery', 'Gallery::index');
$routes->get('/gallery/album/(:any)', 'Gallery::viewAlbum/$1');

// Page Routes
$routes->get('/page/(:any)', 'Pages::view/$1');
$routes->get('/about', 'Pages::about');
$routes->get('/contact', 'Pages::contact');
$routes->post('/contact/submit', 'Contact::submit');

// ==================== ADMIN ROUTES ====================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    
    // Settings
    $routes->get('settings', 'Settings::index');
    $routes->post('settings/update', 'Settings::update');
    $routes->post('settings/upload-logo', 'Settings::uploadLogo');
    $routes->post('settings/upload-favicon', 'Settings::uploadFavicon');
    $routes->post('settings/test-email', 'Settings::testEmail');
    
    // Members Management
    $routes->get('members', 'Members::index');
    $routes->get('members/view/(:num)', 'Members::view/$1');
    $routes->get('members/create', 'Members::create');
    $routes->post('members/store', 'Members::store');
    $routes->get('members/edit/(:num)', 'Members::edit/$1');
    $routes->post('members/update/(:num)', 'Members::update/$1');
    $routes->get('members/delete/(:num)', 'Members::delete/$1');
    $routes->get('members/approve/(:num)', 'Members::approve/$1');
    $routes->post('members/bulk-approve', 'Members::bulkApprove');
    $routes->get('members/export', 'Members::export');
    
    // Executives Management
    $routes->get('executives', 'Executives::index');
    $routes->get('executives/create', 'Executives::create');
    $routes->post('executives/store', 'Executives::store');
    $routes->get('executives/edit/(:num)', 'Executives::edit/$1');
    $routes->post('executives/update/(:num)', 'Executives::update/$1');
    $routes->get('executives/delete/(:num)', 'Executives::delete/$1');
    $routes->post('executives/reorder', 'Executives::reorder');
    
    // Events Management
    $routes->get('events', 'Events::index');
    $routes->get('events/create', 'Events::create');
    $routes->post('events/store', 'Events::store');
    $routes->get('events/edit/(:num)', 'Events::edit/$1');
    $routes->post('events/update/(:num)', 'Events::update/$1');
    $routes->get('events/delete/(:num)', 'Events::delete/$1');
    $routes->get('events/rsvps/(:num)', 'Events::rsvps/$1');
    $routes->get('events/export-rsvps/(:num)', 'Events::exportRsvps/$1');
    
    // News Management
    $routes->get('news', 'News::index');
    $routes->get('news/create', 'News::create');
    $routes->post('news/store', 'News::store');
    $routes->get('news/edit/(:num)', 'News::edit/$1');
    $routes->post('news/update/(:num)', 'News::update/$1');
    $routes->get('news/delete/(:num)', 'News::delete/$1');
    $routes->get('news/toggle-publish/(:num)', 'News::togglePublish/$1');
    
    // Gallery Management
    $routes->get('gallery', 'Gallery::index');
    $routes->get('gallery/create-album', 'Gallery::createAlbum');
    $routes->post('gallery/store-album', 'Gallery::storeAlbum');
    $routes->get('gallery/album/(:num)', 'Gallery::viewAlbum/$1');
    $routes->get('gallery/edit-album/(:num)', 'Gallery::editAlbum/$1');
    $routes->post('gallery/update-album/(:num)', 'Gallery::updateAlbum/$1');
    $routes->get('gallery/delete-album/(:num)', 'Gallery::deleteAlbum/$1');
    $routes->post('gallery/upload-photo/(:num)', 'Gallery::uploadPhoto/$1');
    $routes->get('gallery/delete-photo/(:num)', 'Gallery::deletePhoto/$1');
    $routes->post('gallery/reorder-photos', 'Gallery::reorderPhotos');
    
    // Birthday Wishes Management
    $routes->get('wishes', 'Wishes::index');
    $routes->get('wishes/approve/(:num)', 'Wishes::approve/$1');
    $routes->get('wishes/delete/(:num)', 'Wishes::delete/$1');
    $routes->post('wishes/bulk-approve', 'Wishes::bulkApprove');
    
    // Pages Management
    $routes->get('pages', 'Pages::index');
    $routes->get('pages/create', 'Pages::create');
    $routes->post('pages/store', 'Pages::store');
    $routes->get('pages/edit/(:num)', 'Pages::edit/$1');
    $routes->post('pages/update/(:num)', 'Pages::update/$1');
    $routes->get('pages/delete/(:num)', 'Pages::delete/$1');
    $routes->get('pages/toggle-publish/(:num)', 'Pages::togglePublish/$1');
    
    // Banners Management
    $routes->get('banners', 'Banners::index');
    $routes->get('banners/create', 'Banners::create');
    $routes->post('banners/store', 'Banners::store');
    $routes->get('banners/edit/(:num)', 'Banners::edit/$1');
    $routes->post('banners/update/(:num)', 'Banners::update/$1');
    $routes->get('banners/delete/(:num)', 'Banners::delete/$1');
    $routes->get('banners/toggle-active/(:num)', 'Banners::toggleActive/$1');
    $routes->post('banners/reorder', 'Banners::reorder');

    // Announcements Management
    $routes->get('announcements', 'Announcements::index');
    $routes->post('announcements/store', 'Announcements::store');
    $routes->get('announcements/toggle/(:num)', 'Announcements::toggle/$1');
    $routes->get('announcements/delete/(:num)', 'Announcements::delete/$1');
    
    // Messages Management
    $routes->get('messages', 'Messages::index');
    $routes->get('messages/view/(:num)', 'Messages::view/$1');
    $routes->post('messages/reply/(:num)', 'Messages::reply/$1');
    $routes->get('messages/delete/(:num)', 'Messages::delete/$1');
    $routes->post('messages/bulk-delete', 'Messages::bulkDelete');
    $routes->get('messages/mark-read/(:num)', 'Messages::markAsRead/$1');
    
    // Audit Logs
    $routes->get('audit', 'Audit::index');
    $routes->get('audit/view/(:num)', 'Audit::view/$1');
    $routes->get('audit/export', 'Audit::export');
    $routes->post('audit/clean', 'Audit::clean');


});

    // Admin Reports Routes
$routes->group('admin', function($routes) {
    $routes->get('reports', 'Admin\Reports::index');
    $routes->get('reports/demographics', 'Admin\Reports::demographics');
    $routes->get('reports/export-members', 'Admin\Reports::exportMembers');
    $routes->get('reports/events', 'Admin\Reports::events');
});

// Admin Audit Routes
$routes->group('admin', function($routes) {
    $routes->get('audit', 'Admin\Audit::index');
    $routes->get('audit/export', 'Admin\Audit::export');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */

// Announcement Dismiss
$routes->post('/announcement/dismiss', 'Home::dismissAnnouncement');

/*
 * --------------------------------------------------------------------
 * Catch-all Route for 404 Errors
 * --------------------------------------------------------------------
 */
$routes->set404Override(function() {
    echo view('errors/html/error_404');
});
