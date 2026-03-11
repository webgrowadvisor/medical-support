<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://sdk.twilio.com/js/video/releases/2.29.0/twilio-video.min.js"></script>
    <script>
        const XCSRF_Token = "{{ csrf_token() }}";
    </script>
    <style>
        body {
            margin: 0;
            background: #202124;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            overflow: hidden;
        }

        /* HEADER */
        .call-header {
            position: absolute;
            top: 15px;
            left: 20px;
            z-index: 999;
        }

        .call-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }

        .call-time {
            font-size: 14px;
            opacity: 0.8;
        }

        /* REMOTE VIDEO */
        .remote-video video {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
        }

        /* LOCAL SMALL VIDEO */
        .local-video {
            position: absolute;
            bottom: 100px;
            right: 20px;
            width: 180px;
            height: 130px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.6);
            z-index: 999;
        }

        .local-video video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* CONTROLS */
        .call-controls {
            position: absolute;
            bottom: 25px;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 20px;
            z-index: 999;
        }

        .control-btn {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            border: none;
            background: #3c4043;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .control-btn:hover {
            background: #5f6368;
            transform: scale(1.1);
        }

        .end-btn {
            background: #ea4335;
        }

        .end-btn:hover {
            background: #d93025;
        }

        .active-mic {
            background: #ea4335 !important;
        }


        /* Focus Mode Layout */
        .focus-mode #remote-container,
        .focus-mode #local-container {
            position: relative !important;
            width: 50% !important;
            height: 100vh !important;
            bottom: auto !important;
            right: auto !important;
            border-radius: 0px !important;
        }

        .focus-mode {
            display: flex;
        }

        /* Make both videos full inside container */
        .focus-mode video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .active-focus {
            background: #1a73e8 !important;
        }
      
    </style>
</head>
<body>

<div class="call-header">
    <div class="call-info">
        <h2>{{ $appointment->notes ?? 'Video Call' }}</h2>
        <span class="call-time">⏱ <span id="timer">0:00</span></span>
    </div>
</div>

<div id="remote-container" class="remote-video"></div>
<div id="local-container" class="local-video"></div>

<div class="call-controls">
    <button onclick="toggleMic()" id="micBtn" class="control-btn">
        <i class="fa-solid fa-microphone"></i>
    </button>

    <button onclick="holdCall()" id="holdBtn" class="control-btn">
        <i class="fa-solid fa-pause"></i>
    </button>

    <button onclick="resumeCall()" id="resumeBtn" class="control-btn">
        <i class="fa-solid fa-play"></i>
    </button>

    <button onclick="endCall()" id="endBtn" class="control-btn end-btn">
        <i class="fa-solid fa-phone-slash"></i>
    </button>

    <button onclick="toggleFocus()" id="focusBtn" class="control-btn">
        <i class="fa-solid fa-table-cells"></i>
    </button>
    
</div>

<script>

let activeRoom; // GLOBAL ROOM
let seconds = 0;
let timerInterval;
let callStartTime;
let isMuted = false;
let isVideoOff = false;

// TIMER
function startTimer() {

    // Agar already stored hai to use karo
    const savedStartTime = localStorage.getItem("callStartTime");

    if (savedStartTime) {
        callStartTime = parseInt(savedStartTime);
    } else {
        callStartTime = Date.now();
        localStorage.setItem("callStartTime", callStartTime);
    }

    if (timerInterval) clearInterval(timerInterval);

    timerInterval = setInterval(() => {

        let diff = Math.floor((Date.now() - callStartTime) / 1000);

        let min = Math.floor(diff / 60);
        let sec = diff % 60;

        document.getElementById("timer").innerText =
            min + ":" + (sec < 10 ? "0" + sec : sec);

    }, 1000);
}

function stopTimer() {

    clearInterval(timerInterval);

    localStorage.removeItem("callStartTime");

    document.getElementById("timer").innerText = "0:00";
}

function toggleFocus() {
    const body = document.body;
    const btn = document.getElementById("focusBtn");

    body.classList.toggle("focus-mode");

    btn.classList.toggle("active-focus");
}


setInterval(() => {
    const endBtn   = document.getElementById("endBtn");
    const holdBtn   = document.getElementById("holdBtn");
    const resumeBtn   = document.getElementById("resumeBtn");
    const micsBtn   = document.getElementById("micBtn");

    if (activeRoom) {
        endBtn.disabled = false;
        holdBtn.disabled = false;
        resumeBtn.disabled = false;
        micsBtn.disabled = false;
    } else {
        endBtn.disabled = true;
        holdBtn.disabled = true;
        resumeBtn.disabled = true;
        micsBtn.disabled = true;
    }
}, 100);


    // CONNECT CALL

    var roomName = @json($roomid);

    var username = "user_" + Math.floor(Math.random() * 10000);

    fetch('/video-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': XCSRF_Token
        },
        body: JSON.stringify({
            identity: username,
            room: roomName
        })
    })
    .then(res => res.json())
    .then(data => {
        return Twilio.Video.connect(data.token, {
            name: roomName,
            audio: true,
            video: true,
            frameRate: 24
        });
    })
    .then(room => {

        activeRoom = room; // 🔥 SAVE GLOBALLY
        console.log("Connected:", room.name);
        startTimer();

        const remoteContainer = document.getElementById('remote-container');
        const localContainer = document.getElementById('local-container');

        remoteContainer.innerHTML = "";
        localContainer.innerHTML = "";

        // ✅ LOCAL VIDEO (small preview)
        room.localParticipant.videoTracks.forEach(publication => {
            if (publication.track) {
                const element = publication.track.attach();
                element.muted = true; // avoid echo
                element.playsInline = true;
                localContainer.appendChild(element);
            }
        });

        // ✅ EXISTING REMOTE PARTICIPANTS
        room.participants.forEach(participant => {
            handleParticipant(participant);
        });

        // ✅ NEW PARTICIPANT
        room.on('participantConnected', participant => {
            handleParticipant(participant);
        });

        room.on('disconnected', () => {
            remoteContainer.innerHTML = "";
            localContainer.innerHTML = "";
            stopTimer();
        });

    })
    .catch(error => {
        console.error("Error:", error);
    });


    function handleParticipant(participant) {

        const remoteContainer = document.getElementById('remote-container');

        // ✅ Existing tracks
        participant.tracks.forEach(publication => {
            if (publication.isSubscribed) {
                attachTrack(publication.track, remoteContainer);
            }
        });

        // ✅ New tracks
        participant.on('trackSubscribed', track => {
            attachTrack(track, remoteContainer);
        });

        participant.on('trackUnsubscribed', track => {
            track.detach().forEach(el => el.remove());
        });

    }

    function attachTrack(track, container) {

        const element = track.attach();

        if (track.kind === 'video') {

            container.innerHTML = "";
            element.playsInline = true;
            element.autoplay = true;

            container.appendChild(element);

        }

        if (track.kind === 'audio') {

            element.autoplay = true;
            document.body.appendChild(element);

        }
    }

    // MIC TOGGLE
    function toggleMic() {
        if (!activeRoom) return;

        const micBtn = document.getElementById("micBtn");
        const icon = micBtn.querySelector("i");

        const btn = document.getElementById("micBtn");
        btn.classList.toggle("active-mic");

        activeRoom.localParticipant.audioTracks.forEach(pub => {
            if (isMuted) {
                pub.track.enable();
            } else {
                pub.track.disable();
            }
        });

        isMuted = !isMuted;

        // 🔥 ICON CHANGE
        if (isMuted) {
            icon.classList.remove("fa-microphone");
            icon.classList.add("fa-microphone-slash");
            micBtn.style.backgroundColor = "red";
        } else {
            icon.classList.remove("fa-microphone-slash");
            icon.classList.add("fa-microphone");
            micBtn.style.backgroundColor = "";
        }
    }


    // 🔹 HOLD
    function holdCall() {
        if (!activeRoom) return;

        activeRoom.localParticipant.audioTracks.forEach(p => p.track.disable());
        activeRoom.localParticipant.videoTracks.forEach(p => p.track.disable());
    }


    // 🔹 RESUME
    function resumeCall() {
        if (!activeRoom) return;

        activeRoom.localParticipant.audioTracks.forEach(p => p.track.enable());
        activeRoom.localParticipant.videoTracks.forEach(p => p.track.enable());
    }

    // 🔹 END CALL
    function endCall() {
        if (!activeRoom) return;
        console.clear();
        activeRoom.disconnect();
        stopTimer();
        document.getElementById("video-container").innerHTML = "";
        activeRoom = null;

    }

    function showPlaceholder(username) {

        const container = document.getElementById('video-container');

        // Remove old placeholder if exists
        const old = document.getElementById('user-avatar');
        if (old) old.remove();

        const firstLetter = username.charAt(0).toUpperCase();

        const avatar = document.createElement('div');
        avatar.id = "user-avatar";
        avatar.className = "user-avatar";
        avatar.innerText = firstLetter;

        container.appendChild(avatar);
    }

</script>

</body>
</html>