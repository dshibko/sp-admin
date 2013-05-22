<?php

namespace Application\Model\Helpers;

class MessagesConstants
{

//    Admin Messages Constants
    const INFO_ADMIN_OUT_OF_SEASON = "Out of season";
    const INFO_ADMIN_NO_NEXT_MATCH = "No matches more";
    const INFO_ADMIN_NO_PREV_MATCH = "No matches played";
    const SUCCESS_SEASON_CREATED = "A season was created successfully.";
    const SUCCESS_SEASON_UPDATED = "A season was updated successfully.";
    const SUCCESS_LANDING_UPDATED = "A landing page content was updated successfully.";
    const SUCCESS_GAMEPLAY_BLOCK_CREATED = "A gameplay block was created successfully.";
    const SUCCESS_GAMEPLAY_BLOCK_UPDATED = "A gameplay block was updated successfully.";
    const SUCCESS_GAMEPLAY_BLOCK_DELETED = "A gameplay block was deleted successfully.";
    const ERROR_MAX_GAMEPLAY_BLOCKS_NUMBER = "You can't create more than %s gameplay blocks.";
    const ERROR_GAMEPLAY_BLOCK_NOT_FOUND = "A gameplay block was not found.";

//    Application Messages Constants
    const ERROR_UNKNOWN = "Unknown server error";
    const ERROR_FORM_FILLED_INCORRECTLY = "Form has been filled incorrectly.";
    const ERROR_EMAIL_NOT_REGISTERED = "The email you input is not registered.";
    const ERROR_RECOVERY_LINK_INVALID = "Recovery link is not valid.";
    const ERROR_WRONG_EMAIL_OR_PASSWORD = "Wrong email or password.";
    const ERROR_UPLOAD_FAILED = "Upload filed due to unknown problems.";
    const ERROR_WRONG_DATES_SELECTED = "Wrong start and end dates selected.";
    const ERROR_MATCH_NOT_FOUND = "Match you want to make a prediction was not found.";
    const ERROR_TEAM_NOT_FOUND = "Team you want to make a prediction was not found.";
    const ERROR_PLAYER_NOT_FOUND = "Player you want to mark as a scorer was not found.";
    const ERROR_ACTIVE_PAGE_NOT_FOUND = "Active page was not found.";

    const SUCCESS_RECOVERY_LINK_SENT = "Recovery link was sent to your email. The link is active for 3 hours.";
    const SUCCESS_PASSWORD_CHANGED = "Your password was successfully changed. Now you can login using it.";
    const SUCCESS_CAN_CHANGE_PASSWORD = "Now you can change the password.";

    const INFO_LOGGED_OUT = "You've been logged out.";

    const INFO_OUT_OF_SEASON = "There is no season in play.";

//    Opta Messages Constants
    const WARNING_TEAM_MISSED = "Match data wasn't saved because a team with feeder id %s was not found.";

}
