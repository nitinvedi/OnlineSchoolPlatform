<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #333;
        }
        .container {
            width: 100%;
            height: 100vh;
            text-align: center;
            position: relative;
            box-sizing: border-box;
            padding: 50px;
        }
        .border {
            border: 15px solid #8B5CF6; /* Purple-500 */
            border-radius: 20px;
            height: 100%;
            box-sizing: border-box;
            padding: 40px;
            position: relative;
        }
        .inner-border {
            border: 2px solid #D8B4FE; /* Purple-300 */
            border-radius: 10px;
            height: 100%;
            box-sizing: border-box;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .header {
            font-size: 50px;
            font-weight: bold;
            color: #4C1D95; /* Purple-900 */
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }
        .sub-header {
            font-size: 24px;
            color: #6B7280;
            margin-bottom: 40px;
        }
        .name {
            font-size: 40px;
            font-weight: bold;
            color: #1F2937;
            text-decoration: underline;
            margin-bottom: 40px;
        }
        .course-title {
            font-size: 32px;
            font-weight: bold;
            color: #8B5CF6; /* Purple-500 */
            margin-bottom: 50px;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            text-align: left;
        }
        .signature-block {
            width: 30%;
            text-align: center;
            border-top: 2px solid #4C1D95;
            padding-top: 10px;
        }
        .signature-text {
            font-size: 18px;
            color: #4C1D95;
            font-weight: bold;
        }
        .cert-number {
            font-size: 14px;
            color: #9CA3AF;
            position: absolute;
            bottom: 20px;
            right: 20px;
        }
        .date {
            font-size: 20px;
            color: #4B5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="border">
            <div class="inner-border">
                <div class="header">Certificate of Completion</div>
                <div class="sub-header">This is to proudly certify that</div>
                
                <div class="name">{{ $certificate->user->name }}</div>
                
                <div class="sub-header" style="margin-bottom: 20px;">has successfully completed the course</div>
                
                <div class="course-title">"{{ $certificate->course->title }}"</div>
                
                <div style="margin-top: 60px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 33%; text-align: left; vertical-align: bottom;">
                                <div class="date">Issued on: {{ $certificate->issued_at->format('F j, Y') }}</div>
                            </td>
                            <td style="width: 34%; text-align: center;">
                                <!-- Optional Logo here -->
                                <h1 style="color: #4C1D95; margin: 0;">Live School</h1>
                            </td>
                            <td style="width: 33%; text-align: right; vertical-align: bottom;">
                                <div style="display: inline-block; width: 100%; border-top: 2px solid #4C1D95; padding-top: 10px; text-align: center;">
                                    <div class="signature-text">{{ $certificate->course->instructor->name }}</div>
                                    <div style="font-size: 14px; color: #6B7280;">Instructor</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="cert-number">ID: {{ $certificate->certificate_number }}</div>
            </div>
        </div>
    </div>
</body>
</html>
