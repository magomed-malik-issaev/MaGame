/**
 * Gestionnaire Ajax pour les commentaires et les notes
 */
document.addEventListener('DOMContentLoaded', function () {
    // Configuration CSRF pour les requêtes Ajax
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ===== GESTION DES COMMENTAIRES =====

    // Formulaire d'ajout de commentaire
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const gameId = this.dataset.gameId;
            const commentContent = document.getElementById('comment-content').value;

            if (!commentContent.trim()) {
                showNotification('Veuillez entrer un commentaire', 'error');
                return;
            }

            fetch(`/api/games/${gameId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    content: commentContent
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ajouter le nouveau commentaire à la liste
                        addCommentToList(data);

                        // Réinitialiser le formulaire
                        document.getElementById('comment-content').value = '';

                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Une erreur est survenue', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Une erreur est survenue lors de l\'envoi du commentaire', 'error');
                });
        });
    }

    // Gestion des boutons d'édition de commentaire
    document.addEventListener('click', function (e) {
        if (e.target.closest('.edit-comment-btn')) {
            const commentId = e.target.closest('.edit-comment-btn').dataset.id;
            const commentElement = document.getElementById(`comment-${commentId}`);
            const commentContent = commentElement.querySelector('.comment-content').textContent;

            // Créer un formulaire d'édition
            const editForm = document.createElement('div');
            editForm.className = 'edit-form mt-2';
            editForm.innerHTML = `
                <textarea class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500" rows="3">${commentContent}</textarea>
                <div class="flex justify-end mt-2 space-x-2">
                    <button type="button" class="cancel-edit-btn bg-gray-600 hover:bg-gray-700 text-white text-xs px-3 py-1 rounded-md transition-colors">
                        Annuler
                    </button>
                    <button type="button" class="save-edit-btn bg-purple-600 hover:bg-purple-700 text-white text-xs px-3 py-1 rounded-md transition-colors" data-id="${commentId}">
                        Enregistrer
                    </button>
                </div>
            `;

            // Cacher le contenu actuel et afficher le formulaire d'édition
            commentElement.querySelector('.comment-content').style.display = 'none';
            commentElement.querySelector('.comment-actions').style.display = 'none';
            commentElement.appendChild(editForm);
        }
    });

    // Gestion de l'annulation d'édition
    document.addEventListener('click', function (e) {
        if (e.target.closest('.cancel-edit-btn')) {
            const editForm = e.target.closest('.edit-form');
            const commentElement = editForm.closest('.comment-item');

            // Supprimer le formulaire d'édition et afficher le contenu
            editForm.remove();
            commentElement.querySelector('.comment-content').style.display = 'block';
            commentElement.querySelector('.comment-actions').style.display = 'flex';
        }
    });

    // Gestion de la sauvegarde d'édition
    document.addEventListener('click', function (e) {
        if (e.target.closest('.save-edit-btn')) {
            const commentId = e.target.closest('.save-edit-btn').dataset.id;
            const commentElement = document.getElementById(`comment-${commentId}`);
            const editForm = commentElement.querySelector('.edit-form');
            const newContent = editForm.querySelector('textarea').value;

            if (!newContent.trim()) {
                showNotification('Le commentaire ne peut pas être vide', 'error');
                return;
            }

            fetch(`/api/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    content: newContent
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour le contenu du commentaire
                        commentElement.querySelector('.comment-content').textContent = newContent;

                        // Supprimer le formulaire d'édition et afficher le contenu
                        editForm.remove();
                        commentElement.querySelector('.comment-content').style.display = 'block';
                        commentElement.querySelector('.comment-actions').style.display = 'flex';

                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Une erreur est survenue', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Une erreur est survenue lors de la modification du commentaire', 'error');
                });
        }
    });

    // Gestion de la suppression de commentaire
    document.addEventListener('click', function (e) {
        if (e.target.closest('.delete-comment-btn')) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                return;
            }

            const commentId = e.target.closest('.delete-comment-btn').dataset.id;
            const commentElement = document.getElementById(`comment-${commentId}`);

            fetch(`/api/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Supprimer le commentaire de la liste
                        commentElement.remove();

                        // Mettre à jour le compteur de commentaires
                        updateCommentCount();

                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Une erreur est survenue', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Une erreur est survenue lors de la suppression du commentaire', 'error');
                });
        }
    });

    // ===== GESTION DES NOTES =====

    // Gestion des étoiles de notation
    const ratingStars = document.querySelectorAll('.rating-star');
    if (ratingStars.length) {
        ratingStars.forEach(star => {
            star.addEventListener('click', function (e) {
                e.preventDefault();

                const gameId = this.dataset.gameId;
                const rating = parseInt(this.dataset.rating);

                fetch(`/api/games/${gameId}/ratings`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        rating: rating
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour l'affichage des étoiles
                            updateStarsDisplay(data.rating);

                            // Mettre à jour la note moyenne affichée
                            updateAverageRating(data.averageRating);

                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.message || 'Une erreur est survenue', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Une erreur est survenue lors de l\'envoi de la note', 'error');
                    });
            });
        });
    }

    // Gestion de la suppression de note
    const deleteRatingBtn = document.getElementById('delete-rating-btn');
    if (deleteRatingBtn) {
        deleteRatingBtn.addEventListener('click', function (e) {
            e.preventDefault();

            if (!confirm('Êtes-vous sûr de vouloir supprimer votre note ?')) {
                return;
            }

            const gameId = this.dataset.gameId;

            fetch(`/api/games/${gameId}/ratings`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Réinitialiser l'affichage des étoiles
                        resetStarsDisplay();

                        // Mettre à jour la note moyenne affichée
                        updateAverageRating(data.averageRating);

                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Une erreur est survenue', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Une erreur est survenue lors de la suppression de la note', 'error');
                });
        });
    }

    // ===== FONCTIONS UTILITAIRES =====

    // Ajouter un commentaire à la liste
    function addCommentToList(data) {
        const commentsList = document.getElementById('comments-list');
        const noCommentsMessage = document.querySelector('.no-comments-message');

        // Supprimer le message "Aucun commentaire" s'il existe
        if (noCommentsMessage) {
            noCommentsMessage.remove();
        }

        // Créer l'élément de commentaire
        const commentElement = document.createElement('div');
        commentElement.id = `comment-${data.comment.id}`;
        commentElement.className = 'comment-item bg-gray-700 rounded-lg p-4 mb-4';

        // Vérifier si l'utilisateur est admin
        const adminBadge = data.user.isAdmin ?
            `<span class="ml-2 px-2 py-0.5 bg-red-600 text-white text-xs font-medium rounded-full">Admin</span>` : '';

        commentElement.innerHTML = `
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center">
                    <div class="font-medium text-white">
                        <a href="/profile/${data.comment.user_id}" class="hover:text-purple-400">
                            ${data.user.name}
                        </a>
                    </div>
                    ${adminBadge}
                    <span class="text-xs text-gray-400 ml-2">${data.created_at_formatted}</span>
                </div>
                <div class="comment-actions flex space-x-2">
                    <button class="text-gray-400 hover:text-white edit-comment-btn" data-id="${data.comment.id}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-red-500 delete-comment-btn" data-id="${data.comment.id}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="comment-content text-gray-200">${data.comment.content}</p>
        `;

        // Ajouter le commentaire au début de la liste
        commentsList.insertBefore(commentElement, commentsList.firstChild);

        // Mettre à jour le compteur de commentaires
        updateCommentCount();
    }

    // Mettre à jour le compteur de commentaires
    function updateCommentCount() {
        const commentsCount = document.querySelectorAll('.comment-item').length;
        const commentsTitle = document.getElementById('comments-title');

        if (commentsTitle) {
            commentsTitle.textContent = `Commentaires (${commentsCount})`;
        }
    }

    // Mettre à jour l'affichage des étoiles
    function updateStarsDisplay(rating) {
        const stars = document.querySelectorAll('.rating-star svg');

        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-500');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-500');
            }
        });
    }

    // Réinitialiser l'affichage des étoiles
    function resetStarsDisplay() {
        const stars = document.querySelectorAll('.rating-star svg');

        stars.forEach(star => {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-500');
        });
    }

    // Mettre à jour la note moyenne
    function updateAverageRating(averageRating) {
        const averageRatingElement = document.getElementById('average-rating');

        if (averageRatingElement) {
            averageRatingElement.textContent = averageRating;
        }
    }

    // Afficher une notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 p-4 rounded-md shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Supprimer la notification après 3 secondes
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}); 