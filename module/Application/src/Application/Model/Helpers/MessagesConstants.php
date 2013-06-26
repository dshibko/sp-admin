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
    const SUCCESS_LEAGUE_CREATED = "A league was created successfully.";
    const SUCCESS_LEAGUE_UPDATED = "A league was updated successfully.";
    const SUCCESS_LANDING_UPDATED = "A landing page content was updated successfully.";
    const SUCCESS_GAMEPLAY_BLOCK_CREATED = "A gameplay block was created successfully.";
    const SUCCESS_GAMEPLAY_BLOCK_UPDATED = "A gameplay block was updated successfully.";
    const SUCCESS_GAMEPLAY_BLOCK_DELETED = "A gameplay block was deleted successfully.";
    const SUCCESS_SETTINGS_UPDATED = "Settings were updated successfully.";
    const SUCCESS_FOOTER_IMAGE_ADDED = "A footer image was added successfully.";
    const SUCCESS_FOOTER_IMAGE_DELETED = "A footer image was deleted successfully.";
    const SUCCESS_FOOTER_SOCIAL_CREATED = "A footer social was created successfully.";
    const SUCCESS_FOOTER_SOCIAL_UPDATED = "A footer social was updated successfully.";
    const SUCCESS_FOOTER_SOCIAL_DELETED = "A footer social was deleted successfully.";
    const SUCCESS_LANGUAGE_UPDATED = 'Language was updated successfully';
    const SUCCESS_LANGUAGE_CREATED = 'Language was created successfully';
    const SUCCESS_CLUB_SAVED = 'Club was successfully saved';
    const SUCCESS_SYNC_WITH_FEED = 'Data successfully sync with feed';
    const SUCCESS_PLAYER_SAVED = 'Player was successfully saved';
    const SUCCESS_FIXTURE_SAVED = 'Fixture was successfully saved';
    const SUCCESS_ACCOUNT_SAVED = 'Account was successfully saved';
    const SUCCESS_PRE_MATCH_SHARE_COPY_UPDATED = "Pre match share copy was updated successfully.";
    const SUCCESS_POST_MATCH_SHARE_COPY_UPDATED = "Post match share copy was updated successfully.";
    const SUCCESS_MINI_LEAGUE_DELETE = 'A mini league was deleted successfully';
    const SUCCESS_SEASON_DELETE = 'A season was deleted successfully';
    const SUCCESS_FOOTER_PAGE_SAVED = 'Footer Page successfully saved';
    const SUCCESS_LOGOTYPE_SAVED = 'Logotype was saved successfully';
    const SUCCESS_TERM_SAVED = 'Term was saved successfully';
    const SUCCESS_TERM_DELETED = 'Term was deleted successfully';
    const SUCCESS_OPTA_FEED_PARSED = 'Opta feed was parsed successfully';

    const ERROR_FOOTER_SOCIAL_NOT_DELETED = "A footer social wasn't deleted.";
    const ERROR_FOOTER_IMAGE_NOT_DELETED = "A footer image wasn't deleted.";
    const ERROR_MAX_GAMEPLAY_BLOCKS_NUMBER = "You can't create more than %s gameplay blocks.";
    const ERROR_SOCIAL_NOT_FOUND = "A footer social was not found.";
    const ERROR_GAMEPLAY_BLOCK_NOT_FOUND = "A gameplay block was not found.";
    const ERROR_REGION_NOT_FOUND = "Selected region was not found.";
    const ERROR_LANGUAGE_NOT_FOUND = "Selected language was not found.";
    const ERROR_UPDATE_PO_FILE_FAILED = 'Po file update failed';
    const ERROR_CONVERTING_PO_FILE_TO_MO_FAILED = 'Converting Po file to Mo failed';
    const ERROR_UPDATE_LANGUAGE_COUNTRIES_FAILED = 'Language countries update failed';
    const ERROR_SEASON_DATES_ARE_NOT_AVAILABLE = 'Dates interval you selected intersects with another season\'s interval.';
    const ERROR_LEAGUE_DATES_ARE_NOT_AVAILABLE = 'Dates interval you selected has to be between selected seasons dates.';
    const ERROR_CANNOT_FIND_CLUB = 'Cannot find club';
    const ERROR_CANNOT_FIND_LANGUAGE = 'Cannot find language';
    const ERROR_INVALID_CLUB_ID = 'Invalid Club Id';
    const ERROR_INVALID_PLAYER_ID = 'Invalid Player Id';
    const ERROR_CANNOT_FIND_PLAYER = 'Cannot find player';
    const ERROR_INVALID_FIXTURE_ID = 'Invalid Fixture Id';
    const ERROR_CANNOT_FIND_FIXTURE = 'Cannot find fixture';
    const ERROR_INVALID_USER_ID = 'Invalid user id';
    const ERROR_CANNOT_FIND_USER = 'Cannot find user';
    const ERROR_INVALID_FORM_TYPE = 'Invalid form type';
    const ERROR_UNDEFINED_FOOTER_PAGE_TYPE = 'Undefined footer page type';
    const ERROR_INVALID_TERM_ID = 'Invalid term id';
    const ERROR_CANNOT_FIND_TERM = 'Cannot find term';
    const ERROR_MAX_TERMS_COUNT_EXCEEDED = 'Max terms count exceeded';

//    Application Messages Constants
    const ERROR_FORM_FILLED_INCORRECTLY = "Form has been filled incorrectly.";
    const ERROR_EMAIL_NOT_REGISTERED = "The email you input is not registered.";
    const ERROR_RECOVERY_LINK_INVALID = "Recovery link is not valid.";
    const ERROR_WRONG_EMAIL_OR_PASSWORD = "Wrong email or password.";
    const ERROR_UPLOAD_FAILED = "Upload failed due to unknown problems.";
    const ERROR_WRONG_DATES_SELECTED = "Wrong start and end dates selected.";
    const ERROR_MATCH_NOT_FOUND = "The match was not found.";
    const ERROR_TEAM_NOT_FOUND = "Team you want to make a prediction was not found.";
    const ERROR_ACTIVE_PAGE_NOT_FOUND = "Active page was not found.";
    const ERROR_CANNOT_GET_FACEBOOK_USER_ID_FROM_REQUEST = 'Cannot get facebook user id from request';
    const ERROR_CANNOT_GET_USER_BY_FACEBOOK_ID = 'Cannot get user by facebook id';
    const ERROR_APP_CONFIG_NOT_FOUND = 'Application config was not found.';
    const ERROR_APP_EDITION_CONFIG_NOT_FOUND = 'Application edition config was not found.';
    const ERROR_APP_OPTA_ID_NOT_FOUND = 'Application opta id was not found.';
    const ERROR_APP_OPTA_DIR_PATH_NOT_FOUND = 'Application opta directory path was not found.';
    const ERROR_APP_OPTA_DIR_NOT_EXISTS = 'Application opta directory does not exist.';
    const ERROR_APP_OPTA_DIR_NOT_DIR = 'Application opta directory is not a directory.';
    const ERROR_APP_CLEAR_APP_CACHE_URL_NOT_FOUND = 'Clear application cache was not found.';
    const ERROR_APP_UNKNOWN_EDITION = 'Unknown application edition configured.';
    const ERROR_APP_WRONG_OPTA_CONFIG = 'Wrong opta id configured. Must be an integer.';
    const ERROR_APP_WRONG_EDITION = 'The application is not "%s" edition.';
    const ERROR_PREDICT_THIS_MATCH_NOT_ALLOWED = 'You can\'t predict on this match. You can predict just on %s matches in the future.';
    const ERROR_NO_MORE_MATCHES_IN_THE_SEASON = 'No more matches will be played in this season. Please join us when next season starts.';
    const ERROR_NO_FINISHED_MATCHES_IN_THE_SEASON = 'No matches were played in this season.';
    const ERROR_SECURITY_CHECK_FAILED = 'Bad request. Security check failed.';
    const ERROR_INVALID_SETTING_FORM_TYPE = 'Invalid form type';
    const ERROR_INVALID_OLD_PASSWORD = 'Invalid old password';
    const ERROR_CANNOT_CONNECT_TO_FACEBOOK_ACCOUNT = 'Cannot connect user to facebook account because facebook email has already taken by another user.';

    const SUCCESS_RECOVERY_LINK_SENT = "Recovery link was sent to your email. The link is active for 3 hours.";
    const SUCCESS_USER_RECOVERY_LINK_SENT = 'Recovery link was sent to yout email. The link is active for 60 minutes';
    const SUCCESS_PASSWORD_CHANGED = "Your password was successfully changed. Now you can login using it.";
    const SUCCESS_CAN_CHANGE_PASSWORD = "Now you can change the password.";
    const SUCCESS_NEW_PASSWORD_SAVED = 'New password was successfully saved.';
    const SUCCESS_NEW_EMAIL_SAVED = 'New email was successfully saved.';
    const SUCCESS_NEW_DISPLAY_NAME_SAVED = 'New display name was successfully saved.';
    const SUCCESS_NEW_AVATAR_SAVED = 'New avatar was successfully saved.';
    const SUCCESS_CONNECT_TO_FACEBOOK_ACCOUNT = 'Your account successfully connected to facebook';
    const SUCCESS_NEW_LANGUAGE_SAVED  = 'New language was successfully saved.';
    const SUCCESS_NEW_EMAIL_SETTINGS_SAVED = 'New email settings were successfully saved.';
    const SUCCESS_PUBLIC_PROFILE_OPTION_SAVED = 'Public profile option was successfully saved.';
    const SUCCESS_DELETE_ACCOUNT = 'You have deleted your account successfully.';
    const SUCCESS_HELP_AND_SUPPORT_MESSAGE_SENT = 'Your message successfully sent';

    const INFO_LOGGED_OUT = "You've been logged out.";
    const INFO_OUT_OF_SEASON = "There is no season in play";
    const INFO_OUT_OF_SEASON_DESCRIPTION = "The next one will start soon. You can visit our website later and start playing.";
    const INFO_YOU_PREDICTED_THE_DRAW = "You predicted the draw";
    const INFO_YOU_PREDICTED_THE_WINNER = "You predicted the winner would be %s";
    const INFO_YOU_PREDICTED_THE_SCORE = "You predicted the score would be %s";
    const INFO_YOU_PREDICTED_THE_SCORERS = "You predicted the scorer(s) would be %s";
    const INFO_YOU_PREDICTED_SCORER_ORDER = "You predicted that %s will score goal number %s of %s";
    const INFO_YOUR_ACCURACY = "You were %s accurate in your prediction";
    const INFO_THIS_IS_DOUBLE_POINTS_MATCH = "This is double points match";

    const FAILED_CONNECTION_TO_FACEBOOK = 'Error happened while connecting to your facebook account. Please try again later';
    const FAILED_RETRIEVING_FACEBOOK_DATA = 'Error happened while retrieving your facebook data. Please try again later.';
    const FAILED_UPDATING_DATA_FROM_FACEBOOK = 'Error happened while updating data. Please try again later';
    const FAILED_TO_DELETE_ACCOUNT_INCORRECT_ID = 'You can delete only yours account';
    const FACEBOOK_USER_PASSWORD_RECOVERY = 'Please use Facebook Connect Button to log in.';
    const EXPIRED_RECOVERY_PASSWORD_HASH = 'You need to reset your password again because your recovery link expired.';

    const ACCESS_DENIED_NEED_LOGIN = 'Please sign in to access this page';


    // Opta
    const ERROR_RUN_OUT_OF_CONSOLE = 'Cannot run this action out of console!';
    const ERROR_TYPE_NOT_SPECIFIED = 'You have to specify feed type';
    const ERROR_WRONG_TYPE_SPECIFIED = '"%s" type is not supported';
    const MESSAGE_PREFIX = 'Message: ';
    const INFO_SEASON_NOT_FOUND = 'Feed season was not found';
    const INFO_WRONG_COMPETITION = 'Feed competition is not an application competition';
    const INFO_ENTITY_NOT_FOUND = '"%s" was not found';
    const WARNING_TEAM_NOT_FOUND = 'A team was with team_id = "%s" was not found';
    const ERROR_FIELD_IS_EMPTY = 'The field "%s" is empty';
    const ERROR_CANNOT_BE_PARSED = 'The feed file contents cannot be parsed to xml';

    const APP_CACHE_CLEARED = 'Application cache was cleared successfully';
    const APP_CACHE_NOT_CLEARED = 'Application cache was not cleared';

    const LOG_FEED_IMPORT_STARTED = 'Opta Feed %s Import Started (%s)';
    const LOG_FEED_IMPORT_FINISHED = 'Opta Feed %s Import Finished (%s)';


}
