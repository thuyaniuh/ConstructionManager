<style>
    /* Sidebar styling */
    #sidebar {
        width: 250px;
        transition: all 0.3s;
    }

    .menu {
        padding-top: 5%;
    }

    #sidebar.hide {
        margin-left: -250px;
    }

    /* Main content styling */
    #main-content {
        margin-left: 250px;
        transition: all 0.3s;
    }

    #main-content.expanded {
        margin-left: 0;
    }

    /* Small boxes styling */
    .small-box {
        color: #fff;
        padding: 20px;
        position: relative;
        border-radius: 5px;
    }

    .small-box .icon {
        color: rgba(255, 255, 255, 0.8);
        position: absolute;
        top: 10px;
        right: 30px;
        font-size: 60px;
    }

    .small-box-footer {
        color: rgba(255, 255, 255, 0.8);
        display: block;
        text-align: center;
        padding: 10px;
        background: rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .small-box-footer:hover {
        color: #fff;
        background: rgba(0, 0, 0, 0.2);
    }

    /* Media query for mobile devices */
    @media (max-width: 768px) {
        #sidebar {
            width: 100%;
            margin-left: -100%;
        }

        #sidebar.hide {
            margin-left: 0;
        }

        #main-content {
            margin-left: 0;
            width: 100%;
        }

        /* Small boxes stacking on mobile */
        .small-box {
            margin-bottom: 20px;
            font-size: 1em;
        }

        .small-box .icon {
            position: relative;
            font-size: 40px;
            text-align: left;
        }

        .small-box-footer {
            font-size: 0.9em;
            padding: 5px;
        }
    }

    @media (max-width: 576px) {

        /* Further adjustments for very small screens */
        .small-box {
            font-size: 0.8em;
        }

        .small-box .icon {
            font-size: 35px;
        }

        .small-box-footer {
            font-size: 0.8em;
            padding: 5px;
        }
    }
</style>