<?php include 'includes/header.php'; ?>
<div class="container py-5">
    <div class="mb-5">
        <span class="text-gold fw-bold small text-uppercase">SRS Project Title: LANDBROKER</span>
        <h2 class="fw-bold mt-1">About LBMS & User Guide</h2>
        <p class="text-muted col-lg-8">LBMS is a university-project web platform for land listings, property inquiries and appointments. It brings buyers, sellers/owners, brokers and administrators into one role-based system, with transaction records and controlled access to private documents.</p>
    </div>

    <h4 class="fw-bold mb-3">Who does what?</h4>
    <div class="row g-4 mb-5">
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 h-100"><h6 class="fw-bold text-gold">Buyer</h6><p class="small text-muted mb-0">Search approved listings, ask questions, request visits and follow your related records.</p></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 h-100"><h6 class="fw-bold text-gold">Seller / Owner</h6><p class="small text-muted mb-0">Provide accurate property details and ownership records. Follow listing and transaction progress.</p></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 h-100"><h6 class="fw-bold text-gold">Broker</h6><p class="small text-muted mb-0">Manage assigned listings, reply to inquiries, coordinate visits and maintain transaction and commission records.</p></div></div>
        <div class="col-md-3"><div class="card border-0 shadow-sm p-3 h-100"><h6 class="fw-bold text-gold">Administrator</h6><p class="small text-muted mb-0">Manage accounts and permissions, review listings and access role-based reports. Assigned internally.</p></div></div>
    </div>

    <h4 class="fw-bold mb-3">Frequently Asked Questions</h4>
    <div class="accordion mb-5" id="faqAcc">
        <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#q1">Why isn't my property visible?</button></h2>
            <div id="q1" class="accordion-collapse collapse show"><div class="accordion-body small text-muted">Draft and Pending Verification listings are not public. Submit the listing and follow the authorized reviewer's decision.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header"><button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#q2">Can I change a site visit?</button></h2>
            <div id="q2" class="accordion-collapse collapse"><div class="accordion-body small text-muted">Request a reschedule through your appointment record. The broker coordinates the new time; the previous date stays in history.</div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 bg-white">
        <h5 class="fw-bold">Support & Project Contact</h5>
        <p class="small text-muted mb-2">Prepared By: <strong>Farjana Ferdosy, Mahmudul Hasan, Mahamud ul Alam Supto</strong></p>
        <p class="small text-muted mb-0">University of Information Technology and Sciences (UITS) &bull; SRS Version 2.0 (September 3, 2026)</p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>