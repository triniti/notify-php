# CHANGELOG for 0.x
This changelog references the relevant changes done in 0.x versions.


## v0.3.0
* Add `AzureAndroidNotifier` for Android app notifications.
* Add `AzureIosNotifier` for iOS app notifications.
* Add more unit tests for notifiers.


## v0.2.8
* In `HasNotificationsWatcher::createUpdateNotification` do not set `old_node`.


## v0.2.7
* In `HasNotificationsWatcher::scheduleNotification` since data is immutable, when modifying it, set it to a variable.


## v0.2.6
* Use `DateTimeInterface` typehint instead of `DateTime` to ensure mutable and immutable versions are accepted.


## v0.2.5
* More precise difference checker in `HasNotificationsWatcher::scheduleNotification` before scheduling is executed.


## v0.2.4
* When `HasNotificationsWatcher::scheduleNotification` runs, clone the nodes we get back from search to ensure nothing else is referencing the values we're about to mutate.


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
