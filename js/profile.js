$(document).ready(function() {
	var session_key = localStorage.getItem('session_key');
	$.ajax({
		url: './php/profile.php',
		type: 'GET',
		data: { session_key: session_key},
		dataType: 'json',
		success: function(data) {
			if(data.success)
			{
				$('#username').val(data.username);
				$('#dob').val(data.dob);
				$('#contact-address').val(data.contactAddress);
			}else{
				localStorage.removeItem('session_key');
				window.location.href = 'login.html';
			}
		},
		error: function() {
			alert('Failed to get user profile data.');
		}
	});
	$('#profile-form').submit(function(event) {
		event.preventDefault();
		var session_key = localStorage.getItem('session_key');
		var age = $('#age').val();
		var dob = $('#dob').val();
		var username = $('#username').val();
		var contactAddress = $('#contact-address').val();
		$.ajax({
			url: './php/profile.php',
			type: 'POST',
			data: {
				session_key: session_key,
				username: username,
				age: age,
				dob: dob,
				contactAddress: contactAddress
			},
			success: function(data) {

				alert(data.message);
				alert('Profile updated successfully.');
			},
			error: function() {
				alert('Failed to update profile.');
			}
		});
	});
});