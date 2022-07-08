# AWS Services Setup - notifications

## Prerequisites

- finished basic AWS setup from [here](/resources/docs/aws-services-setup-basic.md).

## Used Services

1. [Amazon SNS](#amazon-simple-notification-service) (Simple Notification Service) - for delivering notification about conversion status to our chosen endpoint. SNSs main elements are topics (basicaly channel for messages) & subscriptions. Topic is like a channel for messages sent to SNS. Each topic can have one or more subscriptions. Subscriptions are like receivers of the messages itself. We will use subscription of the form email address for the simplicity reasons.
2. [Amazon EventBridge](#amazon-eventbridge) - for watching the MediaConvert job queue for success / failure of the conversion -> then will use Amazon SNS for handling this notification 

## Instructions

### Amazon Simple Notification Service
1. open [Amazon SNS Console](https://console.aws.amazon.com/sns)
2. **make sure you have selected desired region in top right selector**
3. if you see only landing page of this service then click on hamburger menu on the left side & click on Topics & click on Create Topic button
4. choose Standard type, choose a name for status topic (for example "conversion_status") & hit Create Topic button
5. let's create first subscription for this newly created topic by hitting Create Subscription button 
6. as Protocol choose Email or Email-JSON and as endpoint put your email address & hit Create Subscription
7. if you have chosen Email protocol - there will be **Confirm subscription** link in the body of the email - click on it
8. if you have chosen Email-JSON - there will be **SubscribeURL** key with the url you need to visit to confirm subscription 
  
### Amazon EventBridge
1. open [Amazon EventBridge Console](https://console.aws.amazon.com/events)
2. **make sure you have selected desired region in top right selector**
3. expand Events in the left menu -> click on Rules > Create rule
4. write the name of the rule, for example "mediaconvert_job_status_changed" -> hit the Next button
5. scroll to the Event pattern section
6. as AWS service choose **MediaConvert**
7. as Event type choose **MediaConvert Job State Change**
8. you can choose between receiving notification (message on SNS topic) about each status change (that way you will receive 3 messages about these 3 changes of the state: PROGRESSING, INPUT_INFORMATION & COMPLETE), or you can choose Specific state(s) and select only those state(s) for which you want to receive notifications
9. click on Next button
10. choose **SNS topic** from a "Select a target" dropdown 
11. choose previously created topic in Topic dropdown & click on Next button
12. you can skip configuring tags by hitting the Next button again
13. review the rule details & hit Create rule button
