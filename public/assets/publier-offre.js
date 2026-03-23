document.addEventListener("DOMContentLoaded", function () {
	const modal = document.getElementById("popUpPublishOffer");
	if (!modal) {
		return;
	}

	const openers = document.querySelectorAll(".button-offre");
	const closeButton = document.getElementById("publishCloseButton");
	const backdrop = modal.querySelector("[data-publish-close='true']");

	function openModal() {
		modal.classList.add("is-open");
		modal.setAttribute("aria-hidden", "false");
		document.body.style.overflow = "hidden";
	}

	function closeModal() {
		modal.classList.remove("is-open");
		modal.setAttribute("aria-hidden", "true");
		document.body.style.overflow = "";
	}

	openers.forEach(function (button) {
		button.addEventListener("click", function (event) {
			event.preventDefault();
			openModal();
		});
	});

	if (closeButton) {
		closeButton.addEventListener("click", closeModal);
	}

	if (backdrop) {
		backdrop.addEventListener("click", closeModal);
	}

	document.addEventListener("keydown", function (event) {
		if (event.key === "Escape" && modal.classList.contains("is-open")) {
			closeModal();
		}
	});
});
