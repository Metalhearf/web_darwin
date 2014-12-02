$(document).ready(function() {
	$('#contactForm').bootstrapValidator({
		container: '#messages',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			loginname: {
				validators: {
					notEmpty: {
						message: 'Champ Identifiant vide.'
					},
					stringLength: {
						max: 25,
						message: 'Identifiant trop long ! (Plus de 25 caractères)'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'Champ Mot de Passe vide.'
					},
					stringLength: {
						max: 50,
						message: 'Mot de passe trop long ! (Plus de 50 caractères)'
					}
				}
			}
		}
	});
});

