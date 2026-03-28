document.addEventListener("DOMContentLoaded", function () {
	function initModal(options) {
		const modal = document.getElementById(options.modalId);
		if (!modal) {
			return null;
		}

		const openers = document.querySelectorAll(options.openSelector);
		const closeButton = document.getElementById(options.closeButtonId);
		const backdrop = modal.querySelector(options.backdropSelector);

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

		return {
			open: openModal,
			close: closeModal,
			modal: modal,
		};
	}

	initModal({
		modalId: "popUpPublishOffer",
		openSelector: ".publish-offer-open",
		closeButtonId: "publishCloseButton",
		backdropSelector: "[data-publish-close='true']",
	});

	const companyModal = initModal({
		modalId: "popUpCreateCompany",
		openSelector: ".button-add-company-open, .add-company-open",
		closeButtonId: "companyCloseButton",
		backdropSelector: "[data-company-close='true']",
	});

	const params = new URLSearchParams(window.location.search);
	if (companyModal && params.has("company")) {
		companyModal.open();
	}
});
