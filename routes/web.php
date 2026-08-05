<?php
/**
 * routes/web.php
 * Defines all application URL routes.
 * TODO: Add all routes in Tugas 1 and expand per Tugas
 */

// --- Public Routes ---
$router->get('/',                   'HomeController@index');
$router->get('/destinations',       'DestinationController@catalog');
$router->get('/destinations/{slug}','DestinationController@detail');

// --- Auth Routes ---
$router->get('/login',    'AuthController@showLogin');
$router->post('/login',   'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register','AuthController@register');
$router->get('/logout',   'AuthController@logout');

// --- OAuth Social Login ---
$router->get('/auth/google',            'AuthController@redirectToGoogle');
$router->get('/auth/google/callback',   'AuthController@handleGoogleCallback');
$router->get('/auth/facebook',          'AuthController@redirectToFacebook');
$router->get('/auth/facebook/callback', 'AuthController@handleFacebookCallback');

// --- User (protected) Routes ---
$router->get('/wishlist',              'WishlistController@index');
$router->post('/wishlist/add',         'WishlistController@add');
$router->post('/wishlist/remove',      'WishlistController@remove');
$router->get('/my-reviews',             'ReviewController@myReviews');
$router->post('/reviews/submit',       'ReviewController@submit');
$router->post('/reviews/update/{id}',  'ReviewController@update');
$router->post('/reviews/delete/{id}',  'ReviewController@delete');


// --- Admin Routes ---
$router->get('/admin',                         'admin\AdminController@dashboard');
$router->get('/admin/categories',              'admin\CategoryController@index');
$router->post('/admin/categories/store',       'admin\CategoryController@store');
$router->post('/admin/categories/update/{id}', 'admin\CategoryController@update');
$router->post('/admin/categories/delete/{id}', 'admin\CategoryController@delete');
$router->get('/admin/destinations',            'admin\DestinationAdminController@index');
$router->get('/admin/destinations/create',     'admin\DestinationAdminController@create');
$router->post('/admin/destinations/store',     'admin\DestinationAdminController@store');
$router->get('/admin/destinations/edit/{id}',  'admin\DestinationAdminController@edit');
$router->post('/admin/destinations/update/{id}','admin\DestinationAdminController@update');
$router->post('/admin/destinations/delete/{id}','admin\DestinationAdminController@delete');
$router->get('/admin/reviews',                 'admin\ReviewAdminController@index');
$router->post('/admin/reviews/hide/{id}',      'admin\ReviewAdminController@hide');
$router->post('/admin/reviews/delete/{id}',    'admin\ReviewAdminController@delete');
$router->post('/admin/destinations/link/save/{id}', 'admin\DestinationLinkAdminController@save');
