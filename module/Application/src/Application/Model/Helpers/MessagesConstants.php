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

    const SUCCESS_RECOVERY_LINK_SENT = "Recovery link was sent to your email. The link is active for 3 hours.";
    const SUCCESS_PASSWORD_CHANGED = "Your password was successfully changed. Now you can login using it.";
    const SUCCESS_CAN_CHANGE_PASSWORD = "Now you can change the password.";
    const SUCCESS_SEASON_CREATED = "A season was created successfully.";
    const SUCCESS_SEASON_UPDATED = "A season was updated successfully.";

    const INFO_LOGGED_OUT = "You've been logged out.";
    const FAILED_CONNECTION_TO_FACEBOOK = 'Error happened while connecting to your facebook account. Please try again later';
    const FAILED_RETRIEVING_FACEBOOK_DATA = 'Error happened while retrieving your facebook data. Please try again later.';
    const FAILED_UPDATING_DATA_FROM_FACEBOOK = 'Error happened while updating data. Please try again later';

}
