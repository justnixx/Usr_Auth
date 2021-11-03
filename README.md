# LogRegPHP

LogRegPHP is a simple authentication module coded in object-oriented PHP. It provides you with some helper classes to help you get on speed with your project.

## Usage

- Download or clone the repository
- Edit and rename the .env.example file to .env
- Run install.php (this will create the users and users_session table for you)
- To check if a user is logged in, create a new user object `$user = new User();` and call the isLoggedIn method `$user->isLoggedIn()`
  
  Example:

   ```PHP
    // Create a new user instance
    $user = new User();

    // If user is not logged in
    if( !$user->isLoggedIn() ) { 

        // Flash a message to the session
        Session::flash('status', 'Unauthorized.');

        // Redirect to the login page 
        Redirect::to('login.php');
    }
   ```

   The above code was taken from the dashboard.php file. For more examples, check the files in the root directory.

## Demo

See it live at [demo.nixx.dev/logregphp](https://demo.nixx.dev/logregphp).

## Bugs

If you discover any bug, please kindly create a new issue.

## Help

If you need any help using the code, please send an email to [hello@nixx.dev](mailto:hello@nixx.dev). I will respond as soon as I can.
