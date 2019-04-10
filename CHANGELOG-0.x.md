# CHANGELOG for 0.x
This changelog references the relevant changes done in 0.x versions.


## v0.2.3
* Ensure we don't run replayed events in `HasNotificationsWatcher::scheduleNotification`.


## v0.2.2
* Add buffer time of 10 seconds when setting `send_at` field to article `published_at`.
* Fix `isNodeRefSupported` to look for correct `$validQNames[]` key.


## v0.2.1
* Add `AppleNewsNotifier` for Apple News notifications.


## v0.2.0
* Add `SendGridEmailNotifier` for email notifications.


## v0.1.1
* Fix HasNotificationsWatcher SearchNotificationsRequest query bug.


## v0.1.0
* Initial version.
