<!-- BEGIN #footer -->
<footer id="footer" class="py-5 bg-gray-900 bg-opacity-75 text-body text-opacity-75" data-bs-theme="dark">
    <div class="container-xxl px-3 px-lg-5">
        <div class="row gx-lg-5 gx-3 gy-lg-4 gy-3">
            <div class="col-lg-3 col-md-6">
                <div class="mb-3">
                    <div class="h2">{{ __('website.footer.brand_title') }}</div>
                </div>
                <p class="mb-4">
                    {{ __('website.footer.brand_description') }}
                </p>
                {{-- <h5>Follow Us</h5>
                <div class="d-flex">
                    <a href="#" class="me-2 text-body text-opacity-50" aria-label="Facebook" rel="noopener"><i class="fab fa-lg fa-facebook fa-fw"></i></a>
                    <a href="#" class="me-2 text-body text-opacity-50"><i class="fab fa-lg fa-instagram fa-fw"></i></a>
                    <a href="#" class="me-2 text-body text-opacity-50"><i class="fab fa-lg fa-twitter fa-fw"></i></a>
                    <a href="#" class="me-2 text-body text-opacity-50"><i class="fab fa-lg fa-youtube fa-fw"></i></a>
                    <a href="#" class="me-2 text-body text-opacity-50"><i class="fab fa-lg fa-linkedin fa-fw"></i></a>
                </div> --}}
            </div>
            <div class="col-lg-3 col-md-6">
                <h5>{{ __('website.footer.quick_links') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.links.newsroom') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.links.company_info') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.links.careers') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.links.for_investors') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.links.brand_resources') }}</a></li>
                </ul>
                <hr class="text-body text-opacity-50">
                <h5>{{ __('website.footer.services') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.services_links.web_development') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.services_links.app_development') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.services_links.seo') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.services_links.marketing') }}</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5>{{ __('website.footer.resources') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.resources_links.documentation') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.resources_links.support') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.resources_links.faqs') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.resources_links.community') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.resources_links.tutorials') }}</a></li>
                </ul>
                <hr class="text-body text-opacity-50">
                <h5>{{ __('website.footer.legal') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.legal_links.privacy_policy') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.legal_links.terms_of_service') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.legal_links.cookie_policy') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.legal_links.compliance') }}</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5>{{ __('website.footer.help_center') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.contact_form') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.live_chat_support') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.portal_help_center') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.email_support') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.technical_documentation') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.service_updates') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.developer_api') }}</a></li>
                    <li class="mb-3px"><a href="#" class="text-decoration-none text-body text-opacity-75">{{ __('website.footer.help_links.knowledge_base') }}</a></li>
                </ul>
            </div>
        </div>
        <hr class="text-body text-opacity-50">
        <div class="row">
            <div class="col-sm-6 mb-3 mb-lg-0">
                <div class="footer-copyright-text">&copy; 2025 Codefanz.com {{ __('website.footer.all_rights_reserved') }}</div>
            </div>
            <div class="col-sm-6 text-sm-end">
                {{-- <div class="dropdown me-4 d-inline">
                    <a href="#" class="text-decoration-none dropdown-toggle text-body text-opacity-50" data-bs-toggle="dropdown">United States (English)</a>
                    <ul class="dropdown-menu">
                        <li><a href="#" class="dropdown-item">United States (English)</a></li>
                        <li><a href="#" class="dropdown-item">China (简体中文)</a></li>
                        <li><a href="#" class="dropdown-item">Brazil (Português)</a></li>
                        <li><a href="#" class="dropdown-item">Germany (Deutsch)</a></li>
                        <li><a href="#" class="dropdown-item">France (Français)</a></li>
                        <li><a href="#" class="dropdown-item">Japan (日本語)</a></li>
                        <li><a href="#" class="dropdown-item">Korea (한국어)</a></li>
                        <li><a href="#" class="dropdown-item">Latin America (Español)</a></li>
                        <li><a href="#" class="dropdown-item">Spain (Español)</a></li>
                    </ul>
                </div> --}}
                <a href="{{ url('sitemap.xml') }}" class="text-decoration-none text-body text-opacity-50">{{ __('website.footer.sitemap') }}</a>
            </div>
        </div>
    </div>
</footer>
<!-- END #footer -->
