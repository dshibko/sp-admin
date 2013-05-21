<?php

namespace Application\Model\Helpers;

class MessagesConstants
{

//    Admin Messages Constants

//    Application Messages Constants
    const ERROR_UNKNOWN = "Unknown server error";
    const ERROR_FORM_FILLED_INCORRECTLY = "Form has been filled incorrectly.";
    const ERROR_EMAIL_NOT_REGISTERED = "The email you input is not registered.";
    const ERROR_RECOVERY_LINK_INVALID = "Recovery link is not valid.";
    const ERROR_WRONG_EMAIL_OR_PASSWORD = "Wrong email or password.";
    const ERROR_UPLOAD_FAILED = "Upload filed due to unknown problems.";
    const ERROR_WRONG_DATES_SELECTED = "Wrong start and end dates selected.";
    const ERROR_INVALID_SETTING_FORM_TYPE = 'Invalid form type';
    const ERROR_INVALID_OLD_PASSWORD = 'Invalid old password';
    const ERROR_UNKNOWN_SIGNED_REQUEST_ALGORITHM = 'Unknown signed_request_algorithm: ';
    const ERROR_BAD_SIGNED_JSON_SIGNATURE = 'Bad Signed JSON signature';
    const ERROR_CANNOT_GET_FACEBOOK_USER_ID_FROM_REQUEST = 'Cannot get facebook user id from signed request. Request: ';
    const ERROR_CANNOT_GET_USER_BY_FACEBOOK_ID = 'Cannot get user by facebook id. Id: ';

    const SUCCESS_RECOVERY_LINK_SENT = "Recovery link was sent to your email. The link is active for 3 hours.";
    const SUCCESS_USER_RECOVERY_LINK_SENT = 'Recovery link was sent to yout email. The link is active for 60 minutes';
    const SUCCESS_PASSWORD_CHANGED = "Your password was successfully changed. Now you can login using it.";
    const SUCCESS_CAN_CHANGE_PASSWORD = "Now you can change the password.";
    const SUCCESS_SEASON_CREATED = "A season was created successfully.";
    const SUCCESS_SEASON_UPDATED = "A season was updated successfully.";
    const SUCCESS_NEW_PASSWORD_SAVED = 'New password successfully saved.';
    const SUCCESS_NEW_EMAIL_SAVED = 'New email successfully saved.';
    const SUCCESS_NEW_DISPLAY_NAME_SAVED = 'New display name successfully saved.';
    const SUCCESS_NEW_AVATAR_SAVED = 'New avatar successfully saved';
    const SUCCESS_NEW_LANGUAGE_SAVED = 'New language successfully saved';
    const SUCCESS_NEW_EMAIL_SETTINGS_SAVED = 'New email settings successfully saved';
    const SUCCESS_PUBLIC_PROFILE_OPTION_SAVED = 'Profile Type option successfully saved';
    const SUCCESS_CONNECT_TO_FACEBOOK_ACCOUNT = 'You successfully connect your account to Facebook';
    const SUCCESS_DELETE_ACCOUNT = 'Your account successfully deleted';

    const INFO_LOGGED_OUT = "You've been logged out.";

    const FAILED_CONNECTION_TO_FACEBOOK = 'Error happened while connecting to your facebook account. Please try again later';
    const FAILED_RETRIEVING_FACEBOOK_DATA = 'Error happened while retrieving your facebook data. Please try again later.';
    const FAILED_UPDATING_DATA_FROM_FACEBOOK = 'Error happened while updating data. Please try again later';
    const FAILED_TO_DELETE_ACCOUNT_INCORRECT_ID = 'You can delete only yours account';
    const FACEBOOK_USER_PASSWORD_RECOVERY = 'Please use Facebook Connect Button to log in.';
    const EXPIRED_RECOVERY_PASSWORD_HASH = 'You need to reset your password again because your recovery link expired.';

    const ACCESS_DENIED_NEED_LOGIN = 'Please sign in to access this page';

}