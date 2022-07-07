# AWS Services Setup

## Prerequisites

- root access to AWS Management Console (using less powerful access is out of scope this example)
  

## Used Services

1. [Amazon S3](#amazon-s3) (Simple Storage Service) - for storage of the files in **buckets**

2. [Amazon MediaConvert](#amazon-mediaconvert) - for conversion of the files (by processing the **jobs**) in selected **queue** 

3. [Amazon SNS](#amazon-simple-notification-service) (Simple Notification Service) - for delivering notification about conversion status to our chosen endpoint. SNSs main elements are topics (basicaly channel for messages) & subscriptions. Topic is like a channel for messages sent to SNS. Each topic can have one or more subscriptions. Subscriptions are like receivers of the messages itself. We will use subscription of the form email address for the simplicity reasons. 

4. [Amazon CloudWatch](#amazon-cloudwatch) - for watching the MediaConvert job queue for success / failure of the conversion -> then will use Amazon SNS for handling this notification 

## Instructions

Make sure you have selected the correct region when working with AWS ecosystem in the top right corner. For the sake of a symplicity choose one and use it for all services in this example. For Europe, you can try **Europe (Frankfurt)** - **eu-central-1**.

### Access Key & Secret

1. Sign in to the [AWS Management Console](https://console.aws.amazon.com/) as the root user

2. In the navigation bar on the upper right, click on your account name and then choose **Security credentials** from the context menu.

3. Expand the Access keys (access key ID and secret access key) section.

4. Choose Create New Access Key. If you already have two access keys, this button is disabled.

5. When prompted, choose Show Access Key or Download Key File. This is your only opportunity to save your secret access key.

Source: https://docs.aws.amazon.com/general/latest/gr/aws-sec-cred-types.html#access-keys-and-secret-access-keys

Fill these two strings in .env file into these variables:
```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
```

### Amazon S3

1. open [Amazon S3 Console](https://s3.console.aws.amazon.com/s3/home)

2. create 3 buckets (bucket name has to be unique in whole AWS, so you need to be creative) + pick region which you will use for whole project - first for source files, second for the converted files, third one for thumbnails

3. fill information about these buckets in .env into these variables (as region use only short version of region - for example "eu-central-1"):
```
AWS_VIDEO_INPUT_REGION=
AWS_VIDEO_INPUT_BUCKET=
AWS_VIDEO_OUTPUT_REGION=
AWS_VIDEO_OUTPUT_BUCKET=
AWS_VIDEO_THUMBNAILS_REGION=
AWS_VIDEO_THUMBNAILS_BUCKET=
```

### Amazon MediaConvert
1. open [Amazon MediaConvert Console](https://console.aws.amazon.com/mediaconvert)
 
2. make sure you have selected desired region in top right selector
 
3. if you see only landing page of this service then click on hamburger menu on the left side & click on Queues -> click on Default queue and copy Queue ARN (it is unique identifier of queue in AWS ecosystem) and save it to this .env variable:
```
AWS_MEDIACONVERT_QUEUE_ARN=
```

4. You will need to create Amazon IAM Role for MediaConvert, please visit [Amazon IAM console](https://console.aws.amazon.com/iam/), click on Roles > Create Role > click on AWS service > from list choose MediaConvert > hit Next button > hit Next: Tags button > hit Next: Review button > as Role name write something meaningfull for you - "MediaConvert" is good enough > hit Create Role button > from roles list choose newly created & click on it > copy Role ARN and fill this .env variable:
```
AWS_MEDIACONVERT_IAM_ROLE_ARN=
```  

5. Save your chosen region identifier into this .env variable  (as region use only short version of region - for example "eu-central-1"):
```
AWS_MEDIACONVERT_REGION=
```

### Amazon Simple Notification Service
1. open [Amazon SNS Console](https://console.aws.amazon.com/sns)

2. make sure you have selected desired region in top right selector

3. if you see only landing page of this service then click on hamburger menu on the left side & click on Topics

3. click on Create Topic button > choose a name for status topic (for example "conversion_status") & hit Create Topic button > topic should be created & you can create first subscription for it by hitting Create Subscription button > as Protocol choose Email or Email-JSON and as endpoint put your email address > hit Create Subscription > check your email and confirm subscription
  
### Amazon CloudWatch
1. open [Amazon CloudWatch Console](https://console.aws.amazon.com/cloudwatch)

2. make sure you have selected desired region in top right selector

3. click on Rules > Create rule

3. as Service Name choose MediaConvert

4. as Event Type choose MediaConvert Job State Change

5. you can choose between receiving notification (message on SNS topic) about each status change (that way you will receive 3 messages about these 3 changes of the state: PROGRESSING, INPUT_INFORMATION & COMPLETE), or you can choose Specific state(s) and select only those state(s) for which you want to receive notifications

5. click on Add Target button > choose SNS topic from a dropdown > choose previously created topic in Topic dropdown > click on Configure details button > write some meaningful name for rule, for example mediaconvert_job_status_changed & hit Create rule button

