<html>
<head>
<style>
    video{
        border: 1px solid black ;
        width: auto;
        height: 150px;
    }

</style>
</head>

<body>

<video id="localVideo" autoplay></video>
<video id="remoteVideo" autoplay></video>

<br/>

<button id="startButton">start</button>
<button id="callButton">call</button>
<button id="hangupButton">hang up</button>


<script>

    var localStream, localPeerConnection, remotePeerConnection;

    var localVideo = document.getElementById('localVideo');
    var remoteVideo = document.getElementById('remoteVideo');

    var startButton = document.getElementById('startButton');
    var callButton = document.getElementById('callButton');
    var hangupButton = document.getElementById('hangupButton');

    startButton.disabled = false;
    callButton.disabled = true;
    hangupButton.disabled = true;

    startButton.onclick = start;
    calltButton.onclick = call;

    function start() {
        startButton.disabled = true;
        navigator.getUserMedia = navigator.getUserMedia ||
        navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        navigator.getUserMedia({video:true}, gotStream,
            function(error) {
                trace('navigator.getUserMedia error: ', error);
            });
    }

    function call(){
        callButton.disabled = true;
        hangupButton.disabled = false;

        if (localStream.getVideoTracks().length > 0) {
            trace('Using video device: ' + localStream.getVideoTracks()[0].label);
        }
        if (localStream.getAudioTracks().length > 0) {
            trace('Using audio device: ' + localStream.getAudioTracks()[0].label);
        }

        var servers = null;

        localPeerConnection = new webkitRTCPeerConnection(servers);
        trace('Created local peer connection object localPeerConnection');
        localPeerConnection.onicecandidate = gotLocalIceCandidate;

        remotePeerConnection = new webkitRTCPeerConnection(servers);
        trace('Created remote peer connection object remotePeerConnection');
        remotePeerConnection.onicecandidate = gotRemoteIceCandidate;
        remotePeerConnection.onaddstream = gotRemoteStream;

        localPeerConnection.addStream(localStream);
        trace('Added localStream to localPeerConnection');
        localPeerConnection.createOffer(gotLocalDescription);
    }

    function gotStream(stream){
        localVideo.src = URL.createObjectURL(stream);
        localStream = stream;
        callButton.disabled = false;
    }

</script>


</body>
</html>