<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Opportunity</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            color: rgba(255,255,255,0.9);
            margin: 10px 0 0;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .job-card {
            background: #f8fafc;
            border-radius: 15px;
            padding: 25px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .job-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 10px;
        }
        .job-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            color: #64748b;
            font-size: 14px;
        }
        .budget {
            background: #e2e8f0;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            color: #1e293b;
        }
        .skills-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .skill-badge {
            display: inline-block;
            background: #e2e8f0;
            color: #1e293b;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            margin: 0 5px 5px 0;
        }
        .skill-badge.match {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 50px;
            font-weight: 600;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .footer {
            background: #f1f5f9;
            padding: 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .match-score {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }
        .client-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        .client-avatar {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .client-details {
            flex: 1;
        }
        .client-name {
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .client-email {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 New Job Opportunity!</h1>
            <p>A job matching your skills has just been posted</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $freelancer->name }}</strong>,</p>
            
            <p>Great news! A new job has been posted that matches your skills on <strong>FreelancerHub</strong>.</p>
            
            @if(isset($matchingSkills) && count($matchingSkills) > 0)
                <div class="match-score">
                    ✨ Matches {{ count($matchingSkills) }} of your skills
                </div>
            @endif
            
            <div class="job-card">
                <h2 class="job-title">{{ $job->title }}</h2>
                
                <div class="job-meta">
                    <span>💰 Budget: <strong>${{ number_format($job->budget, 2) }}</strong></span>
                    <span class="budget">{{ ucfirst($job->status) }}</span>
                </div>
                
                <p>{{ Str::limit($job->description, 200) }}</p>
                
                @if($job->skills && $job->skills->count() > 0)
                <div class="skills-section">
                    <h4 style="margin: 0 0 15px; color: #1e293b;">Required Skills:</h4>
                    @foreach($job->skills as $skill)
                        @php
                            $isMatch = $freelancer->skills && $freelancer->skills->contains($skill->id);
                        @endphp
                        <span class="skill-badge {{ $isMatch ? 'match' : '' }}">
                            @if($isMatch) ✓ @endif
                            {{ $skill->name }}
                        </span>
                    @endforeach
                    
                    @if(isset($matchingSkills) && count($matchingSkills) > 0)
                        <p style="margin: 15px 0 0; color: #10b981; font-size: 14px;">
                            <strong>✓ Skills you have:</strong> 
                            @foreach($matchingSkills as $skill)
                                {{ $skill->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                </div>
                @endif
                
                <div class="client-info">
                    <div class="client-avatar">
                        {{ strtoupper(substr($job->client->name, 0, 1)) }}
                    </div>
                    <div class="client-details">
                        <p class="client-name">{{ $job->client->name }}</p>
                        <p class="client-email">{{ $job->client->email }}</p>
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <a href="{{ route('jobs.show', $job) }}" class="button">
                        View Job Details →
                    </a>
                </div>
                
                <p style="text-align: center; margin: 20px 0 0; color: #64748b; font-size: 14px;">
                    This job was posted {{ $job->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p style="margin: 0 0 10px;">
                <strong>FreelancerHub</strong> - Find your next project
            </p>
            <p style="margin: 0; color: #94a3b8;">
                © {{ date('Y') }} FreelancerHub. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>