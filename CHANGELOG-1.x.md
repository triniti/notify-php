# CHANGELOG for 1.x
This changelog references the relevant changes done in 1.x versions.


## v1.0.10
* Copy context to new scheduled command in `SendNotificationHandler`.


## v1.0.9
* Retry notification up to 3 times in `SendNotificationHandler`.


## v1.0.8
* Change `content-available` to `mutable-content` in aps payload in `AzureIosNotifier` and `FcmIosNotifier` for mutable notification, see https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/ModifyingNotifications.html.


## v1.0.7
* Fix for notification value override issue in the browser notification payload.


## v1.0.6
* Add `fcm_options` with `analytics_label` value to notifications payload in `AbstractFcmNotifier`.


## v1.0.5
* Add `click_action` to and remove `webpush` from the payload in `FcmBrowserNotifier`.


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
