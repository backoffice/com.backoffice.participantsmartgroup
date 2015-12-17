com.backofficethinking.participantsmartgroup
============================================

This CiviCRM extension automatically creates a Smart Group of Registered Participants to an Event when a New Event is created.


## How to Install

1. Download extension from https://github.com/backofficethinking/com.backofficethinking.participantsmartgroup/releases/latest.
2. Unzip / untar the package and place it in your configured extensions directory.
3. When you reload the Manage Extensions page the new “Participant Smart Group Creation” extension should be listed with an Install link.
4. Proceed with install.

Note: this extension does not make any structural changes to the CiviCRM database.


## Basic Use

When creating an event (civicrm/event/add) the New Event form displays an additional checkbox at the bottom: **Create Registered Participant Smart Group**.  The checkbox is checked by default.  If you do not want a Smart Group to be created, uncheck the box.

When the New Event form is submitted, if the checkbox is checked, a new Smart Group will be created.  The Smart Group name will consist of the name of the event appended with the word, **Registrants**.  The Smart Group will be defined as all Participants in the newly created event that have a Participant Status of "Registered".  Thus as new Registered Participants are added to the event, they automatically are available in the new Smart Group.

Note: The created Smart Group is defined as a Group Type of Mailing List.

Note: If a CiviCRM Group already exists with the calculated name, the Smart Group name will be appended with a unique string.


## Warning

Since Smart Groups can add additional load on your webserver, it is highly recommended to delete Smart Groups when no longer useful.


## Example Use Case

An event is created in CiviCRM for an upcoming conference occurring 6 months from now. A CiviCRM Mailing is defined to be sent on a monthly basis to promote the conference. Previous to this extension, the promotional email would be sent to ALL contacts in each monthly mailing, which usually result in multiple registrations for the same contact (who registered each month).

However, with the extension in place, the CiviCRM Mailing is defined to go to ALL contacts, EXCLUDING the registered participants in the newly created Smart Group. Thus, the contacts that have already registered for the event will not receive further promotional emails.


## Credits

This extension was created by BackOffice Thinking (backofficethinking.com) for and sponsored by Keiretsu Forum Mid-Atlantic (http://www.keiretsuforum-midatlantic.com/).

