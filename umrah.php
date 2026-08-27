.umrah-packages-section {
    background: #f8fafc;
}

.umrah-package-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 28px;
}

.umrah-package-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #e7edf3;
    box-shadow: 0 15px 45px rgba(15, 55, 90, .09);
    transition: .25s ease;
}

.umrah-package-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 55px rgba(15, 55, 90, .15);
}

.umrah-package-image {
    height: 330px;
    position: relative;
    background: linear-gradient(135deg,#0d538b,#15a1d5);
}

.umrah-package-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.umrah-image-placeholder {
    width: 100%;
    height: 100%;
    display: grid;
    place-items: center;
    font-size: 75px;
}

.package-featured {
    position: absolute;
    top: 16px;
    left: 16px;
    background: #087ca8;
    color: #fff;
    padding: 6px 11px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 700;
}

.umrah-package-content {
    padding: 22px;
}

.package-top-info {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 12px;
    margin-bottom: 14px;
    border-bottom: 1px dashed #d8e1e8;
}

.package-rating {
    color: #ffb000;
    letter-spacing: 1px;
}

.package-duration {
    color: #73889a;
    font-size: 13px;
}

.package-location {
    color: #8b9aaa;
    font-size: 13px;
    margin-bottom: 7px;
}

.umrah-package-card h3 {
    font-size: 21px;
    margin: 0 0 10px;
    color: #102b45;
}

.package-airline {
    color: #1485c0;
    font-weight: 700;
    margin-bottom: 17px;
}

.package-hotel-info {
    display: grid;
    gap: 12px;
    margin: 15px 0;
    padding: 14px;
    border-radius: 12px;
    background: #f6f9fc;
}

.package-hotel-info strong,
.package-hotel-info span,
.package-hotel-info small {
    display: block;
}

.package-hotel-info span {
    font-size: 14px;
    margin-top: 3px;
}

.package-hotel-info small {
    color: #718597;
    margin-top: 3px;
}

.package-baggage {
    font-size: 13px;
    color: #64798a;
    margin: 14px 0;
}

.package-price {
    margin-top: 18px;
}

.package-price small {
    color: #8495a3;
    display: block;
}

.package-price strong {
    display: block;
    font-size: 27px;
    color: #f47b28;
    margin: 2px 0;
}

.package-price span {
    color: #8a9aa7;
    font-size: 11px;
}

.package-more-btn {
    display: block;
    text-align: center;
    margin-top: 18px;
    background: #f47b28;
    color: #fff;
    text-decoration: none;
    padding: 13px 18px;
    border-radius: 30px;
    font-weight: 800;
}

.package-more-btn:hover {
    background: #df681a;
}

@media (max-width: 950px) {

    .umrah-package-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 620px) {

    .umrah-package-grid {
        grid-template-columns: 1fr;
    }

    .umrah-package-image {
        height: 300px;
    }

}
