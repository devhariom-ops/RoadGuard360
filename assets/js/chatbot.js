/* SafeRoads Floating AI Chatbot Controller (Roady AI) */

let lastBotMessage = "";

function toggleChatbot() {
    const windowEl = document.getElementById('chatbotWindow');
    if (windowEl) {
        windowEl.classList.toggle('d-none');
        if (!windowEl.classList.contains('d-none')) {
            // Focus input on open
            const input = document.getElementById('chatbotInput');
            if (input) input.focus();
        }
    }
}

function sendChatbotMessage(event) {
    if (event) event.preventDefault();
    
    const inputEl = document.getElementById('chatbotInput');
    if (!inputEl) return;
    
    const rawMsg = inputEl.value.trim();
    if (rawMsg === "") return;
    
    // Append User message
    appendChatBubble(rawMsg, 'user-msg');
    inputEl.value = "";
    
    // Process response (delay slightly for realism)
    setTimeout(() => {
        const botResponse = getChatbotResponse(rawMsg);
        lastBotMessage = botResponse;
        appendChatBubble(botResponse, 'bot-msg');
    }, 400);
}

function appendChatBubble(text, className) {
    const container = document.getElementById('chatbotMessages');
    if (!container) return;
    
    const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    
    const bubble = document.createElement('div');
    bubble.className = `chatbot-msg ${className}`;
    bubble.innerHTML = `
        <div class="msg-bubble">${text}</div>
        <div class="msg-time">${timeStr}</div>
    `;
    
    container.appendChild(bubble);
    container.scrollTop = container.scrollHeight;
}

function getChatbotResponse(query) {
    const q = query.toLowerCase();
    
    // Keyword Matching Matrix
    if (q.includes('hello') || q.includes('hi ') || q.includes('hey')) {
        return "Hello! I am Roady, your road safety guide. Ask me about speed limits, seatbelts, helmet physics, zebra crossings, or emergency helplines!";
    }
    
    if (q.includes('speed') || q.includes('limit') || q.includes('fast')) {
        return "Speeding is the leading cause of fatal crashes. Urban speed limits are usually 40-50 km/h, while highway limits range from 80-120 km/h. Speeding quadruples braking impact force!";
    }
    
    if (q.includes('helmet') || q.includes('bike') || q.includes('motorcycle') || q.includes('head')) {
        return "Quality safety helmets absorb crash impact forces, protecting the skull and brain. Wearing a buckled helmet reduces the risk of death by 40% and brain injury by 70%. Keep it buckled!";
    }
    
    if (q.includes('seatbelt') || q.includes('seat belt') || q.includes('belt') || q.includes('buckle')) {
        return "Seatbelts are mandatory for all passengers. During a collision, they spread the deceleration forces across your pelvis and chest, preventing you from flying out of the vehicle.";
    }
    
    if (q.includes('drunk') || q.includes('alcohol') || q.includes('beer') || q.includes('wine') || q.includes('drinking')) {
        return "Drinking alcohol drastically slows neural reflexes, reduces visual accuracy, and alters vehicle control. The legal BAC limit in India is 0.03g/100ml, but the safest limit is 0.00%.";
    }
    
    if (q.includes('emergency') || q.includes('ambulance') || q.includes('police') || q.includes('fire') || q.includes('number') || q.includes('call')) {
        return "Keep these numbers saved: Ambulance (108), Police (112 or 100), Fire Brigade (101). They support one-click calling in our Emergency Center.";
    }
    
    if (q.includes('distract') || q.includes('text') || q.includes('phone') || q.includes('mobile') || q.includes('call')) {
        return "Distracted driving includes texting or browsing. Taking eyes off the road for 5 seconds at highway speeds is like driving blind for 100 meters. Put your phone in Silent/DND!";
    }
    
    if (q.includes('sign') || q.includes('warning') || q.includes('mandatory')) {
        return "We have 3 main sign types: 1. Mandatory signs (circles, red borders), 2. Warning signs (triangles, yellow), 3. Informative signs (squares, blue). View them in 3D in our Signs Guide!";
    }
    
    if (q.includes('rule') || q.includes('pedestrian') || q.includes('zebra') || q.includes('cross')) {
        return "Pedestrians should always cross at Zebra Crossings. Drivers must yield right of way to walking pedestrians. Walk on sidewalks, facing traffic if none exist.";
    }
    
    if (q.includes('calculator') || q.includes('risk') || q.includes('predict')) {
        return "Try the Risk Prediction Calculator at the bottom of the 3D Learning Zone! Input speed, weather, and road conditions to see safety probability ratios.";
    }
    
    if (q.includes('hazard') || q.includes('report') || q.includes('pothole') || q.includes('map')) {
        return "You can report potholes or broken traffic lights on our crowdsourced Hazards Map. Select a location, upload an image, and submit the report.";
    }
    
    return "I am not fully sure I understand that. You can ask me about: Speed limits, Helmet safety, Seatbelts, Drunk driving, Hazard maps, or Emergency numbers!";
}

function readLastChatResponse() {
    if (lastBotMessage === "") {
        speakChatResponse("I'm ready to answer any questions about road safety.");
    } else {
        speakChatResponse(lastBotMessage);
    }
}

function speakChatResponse(text) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = 1.0;
        window.speechSynthesis.speak(utterance);
    }
}
