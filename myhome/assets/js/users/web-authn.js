function webauthnRegister() {
    $.getJSON('webauthn_register_options', function(options) {
        if(options.error!==''){
            alert(options.error);
            return;
        }
        options.challenge = base64ToBuffer(options.challenge);
        options.user.id = base64ToBuffer(options.user.id);
        navigator.credentials.create({ publicKey: options })
            .then(function(credential) {
                $.ajax({
                    url: 'webauthn_verify_register',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(prepareCredentialForServer(credential)),
                    success: function(response) {
                        console.log(response);
                        alert('ثبت با موفقیت انجام شد!');
                    }
                });
            })
            .catch(function(error) {
                console.error(error);
                alert('خطا در ثبت');
            });
    });
}
function webauthnLogin() {
    $.getJSON('webauthn_login_options', function(options) {
        if(options.error!==''){
            alert(options.error);
            return;
        }
        options.challenge = base64ToBuffer(options.challenge);
        options.allowCredentials = options.allowCredentials.map(function(cred) {
            cred.id = base64ToBuffer(cred.id);
            return cred;
        });
        navigator.credentials.get({ publicKey: options }).then(function(assertion) {
            $.ajax({
                url: 'webauthn_verify_login',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(prepareAssertionForServer(assertion)),
                success: function(response) {
                    console.log(response);
                    alert('ورود موفق بود!');
                    window.location.reload();
                }
            });
        }).catch(function(error) {
            console.error(error);
            alert('خطا در ورود');
        });
    });
}
function base64ToBuffer(base64) {
    base64 = base64.replace(/-/g, '+').replace(/_/g, '/');
    const pad = base64.length % 4 ? 4 - base64.length % 4 : 0;
    base64 += '='.repeat(pad);
    const binary = atob(base64);
    const buffer = new ArrayBuffer(binary.length);
    const view = new Uint8Array(buffer);
    for (let i = 0; i < binary.length; i++) {
        view[i] = binary.charCodeAt(i);
    }
    return buffer;
}
function bufferToBase64(buffer) {
    let binary = '';
    const bytes = new Uint8Array(buffer);
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
}
function prepareCredentialForServer(cred) {
    return {
        id: cred.id,
        rawId: bufferToBase64(cred.rawId),
        type: cred.type,
        response: {
            attestationObject: bufferToBase64(cred.response.attestationObject),
            clientDataJSON: bufferToBase64(cred.response.clientDataJSON)
        }
    };
}
function prepareAssertionForServer(assertion) {
    return {
        id: assertion.id,
        rawId: bufferToBase64(assertion.rawId),
        type: assertion.type,
        response: {
            authenticatorData: bufferToBase64(assertion.response.authenticatorData),
            clientDataJSON: bufferToBase64(assertion.response.clientDataJSON),
            signature: bufferToBase64(assertion.response.signature),
            userHandle: assertion.response.userHandle ? bufferToBase64(assertion.response.userHandle) : null
        }
    };
}
function isWebAuthnSupported() {
    return window.PublicKeyCredential && 
    typeof window.PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable === 'function' &&
    'credentials' in navigator && 
    'create' in navigator.credentials;
}
function checkWebAuthnSupport() {
    if (!isWebAuthnSupported()) {
        console.log('WebAuthn not supported');
        $('#webauthn-login').hide();
        $('#webauthn-register').hide();
        return;
    }
    PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable().then(function(supported) {
        if (supported) {
            console.log('WebAuthn with fingerprint supported');
            $('#webauthn-login').show();
            $('#webauthn-register').show();
        } else {
            console.log('Fingerprint not supported');
            $('#webauthn-login').hide();
            $('#webauthn-register').hide();
        }
    });
}
checkWebAuthnSupport();