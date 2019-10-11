# CHANGELOG for 1.x
This changelog references the relevant changes done in 1.x versions.


## v1.0.5
* add `click_action` to and remove `webpush` from the payload in `FcmBrowserNotifier`


## v1.0.4
* Add `FcmBrowserNotifier` for web app notifications.


## v1.0.3
* In `AppleNewsNotifier` add retry with most recent revision token if initial request had stale revision token.


## v1.0.2
* In `NcrNotificationProjector::createSendNotificationJob` make sure send at is for sure in the future.


## v1.0.1
* In `NotificationValidator::ensureNotAlreadyScheduled` also ensure that apple news notifications are included in this check.
* In `NcrNotificationProjector::createSendNotificationJob` always use `pbjx->sendAt(...)` so the sending of the job is async.


## v1.0.0
* First stable version.
* Add ability to use FCM service to send ios and android push notifications.
