<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// HARDCODED DATA ARRAYS (No Database Required)
// ============================================================

// 1. Users
$DATA_USERS = [
    [
        'id' => 1,
        'name' => 'Administrator',
        'email' => 'admin@saferoads.org',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'role' => 'admin',
        'created_at' => '2026-01-01 00:00:00'
    ],
    [
        'id' => 2,
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => password_hash('user123', PASSWORD_DEFAULT),
        'role' => 'user',
        'created_at' => '2026-01-15 00:00:00'
    ]
];

// 2. Reports
$DATA_REPORTS = [
    [
        'id' => 1,
        'user_id' => 2,
        'title' => 'Giant Pothole near Central Crossing',
        'description' => 'A large pothole that covers half the left lane. Extremely dangerous for two-wheelers at night.',
        'latitude' => 12.971598,
        'longitude' => 77.594562,
        'image_path' => 'pothole_mock.jpg',
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
    ],
    [
        'id' => 2,
        'user_id' => 1,
        'title' => 'Broken Traffic Signals',
        'description' => 'The traffic lights at the main highway junction are flashing red continuously, causing massive gridlock.',
        'latitude' => 12.961598,
        'longitude' => 77.604562,
        'image_path' => 'lights_mock.jpg',
        'status' => 'resolved',
        'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
    ]
];

// 3. Blogs
$DATA_BLOGS = [
    [
        'id' => 1,
        'title' => 'The 3-Second Rule: Your Shield Against Rear-End Collisions',
        'excerpt' => 'Discover the simple yet powerful timing technique that can prevent up to 70% of tailgating accidents.',
        'content' => "Rear-end collisions are among the most common types of road accidents, accounting for nearly one-third of all multi-vehicle crashes. Fortunately, they are also the most preventable. The secret lies in maintaining a safe following distance, which is easily managed using the \"3-Second Rule\".\r\n\r\n### How It Works\r\n1. Watch the vehicle ahead of you pass a fixed marker, such as a shadow, billboard, or road sign.\r\n2. Count slowly: \"One thousand and one, one thousand and two, one thousand and three.\"\r\n3. If your vehicle passes the same marker before you finish counting, you are driving too close. Back off to restore a safe gap.\r\n\r\n### When to Increase the Distance\r\nUnder adverse conditions, three seconds is not enough. You should double this distance to 6 seconds when:\r\n- Driving in heavy rain, snow, or wet roads.\r\n- Visibility is reduced by fog, heavy dust, or night darkness.\r\n- Tailing large trucks or buses, which block your view of the road ahead and take much longer to brake.\r\n\r\nApplying this simple rule gives you the critical reaction time needed to stop safely if the driver ahead slams on their brakes. Stay safe, stay back!",
        'image_path' => 'blog_following_distance.jpg',
        'author' => 'Road Safety Committee',
        'created_at' => date('Y-m-d H:i:s', strtotime('-10 days'))
    ],
    [
        'id' => 2,
        'title' => 'Helmets: The Thin Line Between Life and Fatal Injury',
        'excerpt' => 'Exploring the science of how helmets absorb impact forces and why partial helmet use is as bad as none.',
        'content' => "Every year, thousands of two-wheeler riders lose their lives due to head injuries that could have been easily avoided. Wearing a helmet is not just a legal obligation; it is a vital life support system. According to the World Health Organization (WHO), wearing a quality safety helmet can reduce the risk of death by 40% and severe brain injury by over 70%.\r\n\r\n### The Anatomy of an Impact\r\nWhen a motorcycle crashes, the rider is often thrown off. Upon impact, the head experiences sudden deceleration. A helmet acts as a shock absorber through its key components:\r\n1. **The Outer Shell**: Made of tough plastics or carbon fiber, it distributes the force of impact over a wider area and protects against skull penetration.\r\n2. **The Impact Absorbing Liner**: Typically made of expanded polystyrene (EPS), this foam crushes slowly, cushioning the brain from slamming against the skull.\r\n3. **Comfort Padding**: Fits snugly to keep the helmet in place.\r\n4. **The Chinstrap**: Ensures the helmet remains attached during a collision. An unbuckled helmet is useless!\r\n\r\n### Buying Tips\r\nAlways look for safety certifications (e.g., DOT, ECE, or ISI). Avoid cheap, uncertified plastic caps. Replace your helmet immediately after any impact, even if it looks fine, as the inner EPS liner can only crush once. Keep it buckled and ride safe!",
        'image_path' => 'blog_helmet_safety.jpg',
        'author' => 'Safety Officer',
        'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
    ]
];

// 4. Quiz Questions
$DATA_QUIZ_QUESTIONS = [
    [
        'id' => 1,
        'question' => 'What does a flashing red traffic light mean?',
        'option_a' => 'Stop completely and proceed only when safe',
        'option_b' => 'Slow down and proceed with caution',
        'option_c' => 'Speed up to cross the intersection',
        'option_d' => 'Wait for it to turn green',
        'correct_option' => 'A',
        'explanation' => 'A flashing red light has the same meaning as a stop sign: you must come to a complete stop, yield to any cross traffic, and proceed when clear.'
    ],
    [
        'id' => 2,
        'question' => 'When is it legal to use a mobile phone while driving?',
        'option_a' => 'Only with a hands-free device, but it is still discouraged',
        'option_b' => 'Whenever you are stopped at a red light',
        'option_c' => 'For quick text messages only',
        'option_d' => 'Any time you want',
        'correct_option' => 'A',
        'explanation' => 'Hands-free systems are legal in many areas, but any phone use distracts from driving. It is best to avoid phone use entirely while driving.'
    ],
    [
        'id' => 3,
        'question' => 'What is the main purpose of a zebra crossing?',
        'option_a' => 'To provide a designated safe path for pedestrians to cross the road',
        'option_b' => 'To decorate the road surface with stripes',
        'option_c' => 'To guide cars into lanes',
        'option_d' => 'To mark where cars should speed up',
        'correct_option' => 'A',
        'explanation' => 'A zebra crossing gives pedestrians the right-of-way to cross the road safely. Drivers must yield to pedestrians on it.'
    ],
    [
        'id' => 4,
        'question' => 'What does a solid double yellow line in the center of the road indicate?',
        'option_a' => 'No overtaking/passing allowed in either direction',
        'option_b' => 'Passing is allowed from both sides',
        'option_c' => 'The road is under construction',
        'option_d' => 'Overtake only at high speeds',
        'correct_option' => 'A',
        'explanation' => 'Double solid yellow lines mean that passing/overtaking is prohibited for vehicles traveling in both directions.'
    ],
    [
        'id' => 5,
        'question' => 'What should you do when an emergency vehicle approaches with sirens?',
        'option_a' => 'Pull over to the left/edge of the road and stop to clear the way',
        'option_b' => 'Maintain your speed and lane',
        'option_c' => 'Speed up to stay ahead of it',
        'option_d' => 'Stop immediately in the middle of your lane',
        'correct_option' => 'A',
        'explanation' => 'Drivers must safely pull over to the side of the road and stop to allow emergency vehicles to pass without delay.'
    ],
    [
        'id' => 6,
        'question' => 'What is the rule of thumb for safe tailing distance behind another vehicle?',
        'option_a' => 'The 3-second rule',
        'option_b' => 'Half a car length',
        'option_c' => '10 meters regardless of speed',
        'option_d' => 'Close enough to read the license plate',
        'correct_option' => 'A',
        'explanation' => 'The 3-second rule ensures a safe stopping distance under normal dry conditions. This should be increased in wet or foggy weather.'
    ],
    [
        'id' => 7,
        'question' => 'What color are mandatory road signs in general?',
        'option_a' => 'Blue or White circle with red border',
        'option_b' => 'Yellow triangles',
        'option_c' => 'Green rectangles',
        'option_d' => 'Orange diamonds',
        'correct_option' => 'A',
        'explanation' => 'Mandatory signs (like Stop, Speed Limits, No Entry) are generally circular with red borders or blue backgrounds.'
    ],
    [
        'id' => 8,
        'question' => 'What is the legal blood alcohol concentration (BAC) limit for driving?',
        'option_a' => '0.05% or 0.08% depending on the country, but zero is safest',
        'option_b' => '0.50%',
        'option_c' => 'There is no limit',
        'option_d' => '0.20%',
        'correct_option' => 'A',
        'explanation' => 'Most countries enforce a BAC limit of 0.05% or 0.08%. Driving with any alcohol in your system increases accident risks significantly.'
    ]
];

// 5. Quiz Attempts
$DATA_QUIZ_ATTEMPTS = [
    [
        'id' => 1,
        'user_id' => 2,
        'score' => 6,
        'total_questions' => 8,
        'percentage' => 75.00,
        'attempted_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
    ]
];

// 6. Videos
$DATA_VIDEOS = [
    [
        'id' => 1,
        'title' => 'Understanding Traffic Signs & Rules',
        'youtube_id' => '8i6Xp4k2c3M',
        'category' => 'Traffic Rules',
        'description' => 'A comprehensive guide explaining the meanings of mandatory, cautionary, and informative signs for new drivers.'
    ],
    [
        'id' => 2,
        'title' => 'The Danger of Overspeeding',
        'youtube_id' => '4WzPebqE3rM',
        'category' => 'Accident Prevention',
        'description' => 'A crash physics breakdown showing how stopping distances multiply with speed, and the dramatic impact force changes.'
    ],
    [
        'id' => 3,
        'title' => 'Basic First Aid for Road Crash Victims',
        'youtube_id' => 'h8yN27bE_dE',
        'category' => 'Emergency Response',
        'description' => 'Learn the recovery position, how to control bleeding, and what NOT to do before professional emergency help arrives.'
    ],
    [
        'id' => 4,
        'title' => 'Defensive Driving Techniques',
        'youtube_id' => '3Wp6A8u0c2A',
        'category' => 'Safe Driving Tips',
        'description' => 'Essential tips for anticipating potential hazards, managing intersections, and driving safely in severe weather or low visibility.'
    ]
];

// 7. Video Interactions
$DATA_VIDEO_INTERACTIONS = [
    [
        'id' => 1,
        'user_id' => 2,
        'video_id' => 1,
        'is_liked' => 1,
        'is_bookmarked' => 1,
        'comment' => 'This was incredibly helpful for my driving exam! High quality graphics.',
        'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
    ],
    [
        'id' => 2,
        'user_id' => 1,
        'video_id' => 2,
        'is_liked' => 1,
        'is_bookmarked' => 0,
        'comment' => 'Everyone needs to watch this video before getting their license. Speeding kills.',
        'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours'))
    ]
];

// 8. Feedbacks
$DATA_FEEDBACKS = [
    [
        'id' => 1,
        'name' => 'John Smith',
        'email' => 'john.smith@gmail.com',
        'subject' => 'Great initiative',
        'message' => 'The 3D simulator is extremely educational. My kids love playing with it and learning traffic rules!',
        'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
    ]
];

// ============================================================
// HELPER: Get user name by ID
// ============================================================
function get_user_name_by_id($user_id) {
    global $DATA_USERS;
    foreach ($DATA_USERS as $u) {
        if ($u['id'] == $user_id) {
            return $u['name'];
        }
    }
    return 'Anonymous';
}
?>
