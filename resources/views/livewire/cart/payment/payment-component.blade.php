@extends('layouts.cart.payment')

@section('content')

<div>
    <div class="privacy">
        <div class="container">
            <div class="header-bot">
                <div class="header-bot_inner_wthreeinfo_header_mid">
                    <!-- header-bot-->
                    <div class="col-md-4 logo_agile">
                        <img class="col-md-4" src="http://127.0.0.1:8000/images/BarBer.png" alt=" "
                            style="width: 100px;height: 70px;">
                        <h3 class="col-md-8">
                            Empresa de Pruebas
                            <span>S</span>hoppy
                        </h3>
                    </div>
                    <!-- header-bot -->
                    <div class="col-md-6 header flex">
                        <!-- search -->
                        <div class="agileits_search col-12 flex">
                            <input name="Search" type="search" placeholder="En qué podemos ayudarte?" required="">
                            <button type="submit" class="btn btn-default" aria-label="Left Align">
                                <span class="fa fa-search" aria-hidden="true"> </span>
                            </button>
                        </div>
                        <!-- //search -->
                        <!-- cart details -->
                        <div class="top_nav_right col-3">
                            <div class="wthreecartaits wthreecartaits2 cart cart box_1">

                                <button class="w3view-cart" type="submit" name="submit" value="">
                                    <i class="fa fa-cart-arrow-down" aria-hidden="true" wire:click="show_carrito()"></i>
                                </button>

                            </div>
                        </div>
                        <!-- //cart details -->

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- tittle heading -->
            <h3 class="tittle-w3l">{{ __('labels.Payment') }}
                <span class="heading-style">
                    <i></i>
                    <i></i>
                    <i></i>
                </span>
            </h3>

            <div class="accordion flex" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCero">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCero" aria-expanded="true" aria-controls="collapseCero">
                            {{ __('labels.Cash_on_delivery') }}
                        </button>
                    </h2>
                    <div id="collapseCero" class="accordion-collapse collapse show" aria-labelledby="headingCero"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            {{-- <div class="resp-tab-content hor_1" aria-labelledby="hor_1_tab_item-0"> --}}
                            <div class="vertical_post check_box_agile">
                                <h5>COD</h5>
                                <div class="checkbox">
                                    <div class="check_box_cero cashon_delivery">
                                        <label class="anim">
                                            <input type="checkbox" class="checkbox">
                                            <span>{{ __('labels.Important_Cash_on_delivery_espanol') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {{ __('labels.Credit_DebitNet_Banking') }}
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form action="#" method="post" class="creditly-card-form agileinfo_form">
                                <div class="creditly-wrapper wthree, w3_agileits_wrapper">
                                    <div class="credit-card-wrapper">
                                        <div class="first-row form-group">
                                            <div class="controls">
                                                <label class="control-label">{{ __('labels.Name_on_Card') }}</label>
                                                <input class="billing-address-name form-control" type="text" name="name"
                                                    placeholder="John Smith">
                                            </div>
                                            <div class="w3_agileits_card_number_grids">
                                                <div class="w3_agileits_card_number_grid_left">
                                                    <div class="controls">
                                                        <label
                                                            class="control-label">{{ __('labels.Card_Number') }}</label>
                                                        <input class="number credit-card-number form-control"
                                                            type="text" name="number" inputmode="numeric"
                                                            autocomplete="cc-number" autocompletetype="cc-number"
                                                            x-autocompletetype="cc-number"
                                                            placeholder="•••• •••• •••• ••••">
                                                    </div>
                                                </div>
                                                <div class="w3_agileits_card_number_grid_right">
                                                    <div class="controls">
                                                        <label class="control-label">CVV</label>
                                                        <input class="security-code form-control" Â·=""
                                                            inputmode="numeric" type="text" name="security-code"
                                                            placeholder="•••">
                                                    </div>
                                                </div>
                                                <div class="clear"> </div>
                                            </div>
                                            <div class="controls">
                                                <label
                                                    class="control-label">{{ __('labels.Expiration_Date') }}</label>
                                                <input class="expiration-month-and-year form-control" type="text"
                                                    name="expiration-month-and-year" placeholder="MM / YY">
                                            </div>
                                        </div>
                                        <div
                                            class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
                                            <input type="button" style="margin-bottom: 10px; border-radius: 5px;"
                                                value="{{ __('labels.Make_Payment') }}" class="button"
                                                wire:click="Agregar(1)">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {{ __('labels.Net_Banking') }}
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="vertical_post">
                                <form action="#" method="post">
                                    <h5>{{ __('labels.Select_From_Popular_Banks') }}</h5>
                                    <div class="swit-radio">
                                        <div class="check_box_one">
                                            <div class="radio_one">
                                                <label>
                                                    <input type="radio" name="radio" checked="checked">
                                                    <i></i>Syndicate Bank</label>
                                            </div>
                                        </div>
                                        <div class="check_box_one">
                                            <div class="radio_one">
                                                <label>
                                                    <input type="radio" name="radio">
                                                    <i></i>Bank of Baroda</label>
                                            </div>
                                        </div>
                                        <div class="check_box_one">
                                            <div class="radio_one">
                                                <label>
                                                    <input type="radio" name="radio">
                                                    <i></i>Canara Bank</label>
                                            </div>
                                        </div>
                                        <div class="check_box_one">
                                            <div class="radio_one">
                                                <label>
                                                    <input type="radio" name="radio">
                                                    <i></i>ICICI Bank</label>
                                            </div>
                                        </div>
                                        <div class="check_box_one">
                                            <div class="radio_one">
                                                <label>
                                                    <input type="radio" name="radio">
                                                    <i></i>State Bank Of India</label>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <h5>{{ __('labels.Or_Select_Other_Bank') }}</h5>
                                    <div class="section_room_pay">
                                        <select class="year">
                                            <option value="" selected="selected">=== Other Banks ===</option>
                                            <option value="ALB-NA">Allahabad Bank NetBanking</option>
                                            <option value="ADB-NA">Andhra Bank</option>
                                            <option value="BBK-NA">Bank of Bahrain and Kuwait NetBanking</option>
                                            <option value="BBC-NA">Bank of Baroda Corporate NetBanking</option>
                                            <option value="BBR-NA">Bank of Baroda Retail NetBanking</option>
                                            <option value="BOI-NA">Bank of India NetBanking</option>
                                            <option value="BOM-NA">Bank of Maharashtra NetBanking</option>
                                            <option value="CSB-NA">Catholic Syrian Bank NetBanking</option>
                                            <option value="CBI-NA">Central Bank of India</option>
                                            <option value="CUB-NA">City Union Bank NetBanking</option>
                                            <option value="CRP-NA">Corporation Bank</option>
                                            <option value="DBK-NA">Deutsche Bank NetBanking</option>
                                            <option value="DCB-NA">Development Credit Bank</option>
                                            <option value="DC2-NA">Development Credit Bank - Corporate</option>
                                            <option value="DLB-NA">Dhanlaxmi Bank NetBanking</option>
                                            <option value="FBK-NA">Federal Bank NetBanking</option>
                                            <option value="IDS-NA">Indusind Bank NetBanking</option>
                                            <option value="IOB-NA">Indian Overseas Bank</option>
                                            <option value="ING-NA">ING Vysya Bank (now Kotak)</option>
                                            <option value="JKB-NA">Jammu and Kashmir NetBanking</option>
                                            <option value="JSB-NA">Janata Sahakari Bank Limited</option>
                                            <option value="KBL-NA">Karnataka Bank NetBanking</option>
                                            <option value="KVB-NA">Karur Vysya Bank NetBanking</option>
                                            <option value="LVR-NA">Lakshmi Vilas Bank NetBanking</option>
                                            <option value="OBC-NA">Oriental Bank of Commerce NetBanking</option>
                                            <option value="CPN-NA">PNB Corporate NetBanking</option>
                                            <option value="PNB-NA">PNB NetBanking</option>
                                            <option value="RSD-DIRECT">Rajasthan State Co-operative Bank-Debit Card
                                            </option>
                                            <option value="RBS-NA">RBS (The Royal Bank of Scotland)</option>
                                            <option value="SWB-NA">Saraswat Bank NetBanking</option>
                                            <option value="SBJ-NA">SB Bikaner and Jaipur NetBanking</option>
                                            <option value="SBH-NA">SB Hyderabad NetBanking</option>
                                            <option value="SBM-NA">SB Mysore NetBanking</option>
                                            <option value="SBT-NA">SB Travancore NetBanking</option>
                                            <option value="SVC-NA">Shamrao Vitthal Co-operative Bank</option>
                                            <option value="SIB-NA">South Indian Bank NetBanking</option>
                                            <option value="SBP-NA">State Bank of Patiala NetBanking</option>
                                            <option value="SYD-NA">Syndicate Bank NetBanking</option>
                                            <option value="TNC-NA">Tamil Nadu State Co-operative Bank NetBanking
                                            </option>
                                            <option value="UCO-NA">UCO Bank NetBanking</option>
                                            <option value="UBI-NA">Union Bank NetBanking</option>
                                            <option value="UNI-NA">United Bank of India NetBanking</option>
                                            <option value="VJB-NA">Vijaya Bank NetBanking</option>
                                        </select>
                                    </div>
                                    <div
                                        class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
                                        <input type="button" style="margin-bottom: 10px; border-radius: 5px;"
                                            value="{{ __('labels.Pay_Now') }}" class="button"
                                            wire:click="Agregar(1)">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            {{ __('labels.Paypal_Account') }}
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div id="tab4" class="tab-grid" style="display: block;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img class="pp-img" src="{{ asset('images/paypal.png') }}"
                                            alt="Image Alternative text" title="Image Title">
                                        <p>{{ __('labels.Important') }}</p>
                                        <div class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out"
                                            style="text-align: center;">
                                            <a class="btn btn-primary">{{ __('labels.Checkout_via_Paypal') }}</a>
                                        </div>
                                        <br><br><br>
                                        <div
                                            class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
                                            <input type="button" style="margin-bottom: 10px; border-radius: 5px;"
                                                value="{{ __('labels.Proceed_Payment') }}" class="button"
                                                wire:click="Agregar(1)">
                                        </div>
                                    </div>
                                    <div class="col-md-6 number-paymk">
                                        <form class="cc-form">
                                            <div class="clearfix">
                                                <div class="form-group form-group-cc-number">
                                                    <label>{{ __('labels.Card_Number') }}</label>
                                                    <input class="form-control" placeholder="xxxx xxxx xxxx xxxx"
                                                        type="text">
                                                    <span class="cc-card-icon"></span>
                                                </div>
                                                <div class="form-group form-group-cc-cvc">
                                                    <label>CVV</label>
                                                    <input class="form-control" placeholder="xxxx" type="text">
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="form-group form-group-cc-name">
                                                    <label>{{ __('labels.Card_Holder_Name') }}</label>
                                                    <input class="form-control" type="text">
                                                </div>
                                                <div class="form-group form-group-cc-date">
                                                    <label>{{ __('labels.Expiration_Date') }}</label>
                                                    <input class="form-control" placeholder="mm/yy" type="text">
                                                </div>
                                            </div>
                                            <div class="checkbox checkbox-small">
                                                <label>
                                                    <input class="i-check" type="checkbox"
                                                        checked="checked">{{ __('labels.Add_to_My_Cards') }}</label>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            {{ __('labels.MercadoPago_Account') }}
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div id="tab4" class="tab-grid" style="display: block;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img class="pp-img" src="{{ asset('images/mercadopago.png') }}"
                                            alt="Image Alternative text" title="Image Title">
                                        <p>{{ __('labels.Important') }}</p>
                                        <div class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out"
                                            style="text-align: center;">
                                            <a class="btn btn-primary">{{ __('labels.Checkout_via_Paypal') }}</a>
                                        </div>
                                        <br><br><br>
                                        <div
                                            class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">
                                            <input type="button" style="margin-bottom: 10px; border-radius: 5px;"
                                                value="{{ __('labels.Proceed_Payment') }}" class="button"
                                                wire:click="Agregar(1)">
                                        </div>
                                    </div>
                                    <div class="col-md-6 number-paymk">
                                        <form class="cc-form">
                                            <div class="clearfix">
                                                <div class="form-group form-group-cc-number">
                                                    <label>{{ __('labels.Card_Number') }}</label>
                                                    <input class="form-control" placeholder="xxxx xxxx xxxx xxxx"
                                                        type="text">
                                                    <span class="cc-card-icon"></span>
                                                </div>
                                                <div class="form-group form-group-cc-cvc">
                                                    <label>CVV</label>
                                                    <input class="form-control" placeholder="xxxx" type="text">
                                                </div>
                                            </div>
                                            <div class="clearfix">
                                                <div class="form-group form-group-cc-name">
                                                    <label>{{ __('labels.Card_Holder_Name') }}</label>
                                                    <input class="form-control" type="text">
                                                </div>
                                                <div class="form-group form-group-cc-date">
                                                    <label>{{ __('labels.Expiration_Date') }}</label>
                                                    <input class="form-control" placeholder="mm/yy" type="text">
                                                </div>
                                            </div>
                                            <div class="checkbox checkbox-small">
                                                <label>
                                                    <input class="i-check" type="checkbox"
                                                        checked="checked">{{ __('labels.Add_to_My_Cards') }}</label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
