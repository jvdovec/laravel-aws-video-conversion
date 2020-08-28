# AWS Services Setup

## Prerequisites

- root access to AWS Management Console (using less powerful access if out of scope this example)
  

## Used Services

1. [Amazon S3](#amazon-s3) (Simple Storage Service) - for storage of the files

2. [Amazon MediaConvert](#amazon-mediaconvert) - for conversion of the files

3. [Amazon CloudWatch](#amazon-cloudwatch) - for watching the MediaConvert job queue for success / failure of the conversion -> then will use Amazon SNS as target for sending notification 

4. [Amazon SNS](#amazon-sns) (Simple Notification Service) - for sending request to our endpoint about conversion status change

## Instructions

Make sure you have selected the correct region when working with AWS ecosystem in the top right corner. For the sake of a symplicity choose one and use it for all services in this example. For Europe, you can try **Europe (Frankfurt)** - **eu-central-1**.

### Access Key & Secret

1. Sign in to the AWS Management Console as the root user

2. In the navigation bar on the upper right, choose your account name or number and then choose My Security Credentials.

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

3. fill information about these buckets in .env into these variables:
```
AWS_VIDEO_INPUT_REGION=
AWS_VIDEO_INPUT_BUCKET=
AWS_VIDEO_OUTPUT_REGION=
AWS_VIDEO_OUTPUT_BUCKET=
AWS_VIDEO_THUMBNAILS_REGION=
AWS_VIDEO_THUMBNAILS_BUCKET=
```
