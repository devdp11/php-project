<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Flow</title>

  <script src="/js/index.js"></script>
  <link rel="stylesheet" href="/css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <header class="position-fixed">
    <div class="Navheader max-width-1250">

      <div class="menu-toggle my-3 border-0" id="menuToggle">
        <button type="button" class="btn" onclick="toggleMenu()">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
              d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
          </svg>
        </button>
        <a href="#home" class="logo_media"><img src="/assets/logo.png" alt="ExpenseFlow"></a>
      </div>

      <div class="Navbar-area display-flex justify-content-between align-items-center" id="menu">

        <a href="#home" class="logo"><img src="/assets/logo.png" alt="ExpenseFlow"></a>

        <a class="text-decoration-none position-relative" href="#home" onclick="removeActiveClass()">Home</a>
        <a class="text-decoration-none position-relative" href="#workdy" onclick="removeActiveClass()">Work Dynamics</a>
        <a class="text-decoration-none position-relative" href="#about-us" onclick="removeActiveClass()">About us</a>
        <a class="text-decoration-none position-relative" href="#contacts" onclick="removeActiveClass()">Contacts</a>


        <div class="btn">
          <a class="text-decoration-none m-r-20 position-relative rounded px- py-1" href="login">Login</a>
          <a class="text-decoration-none position-relative rounded px-2 py-1" href="register">Register</a>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section id="home" class="section pt-5 pb-5">
      <div class="container max-width-1250">
        <div class="row">
          <div class="col-lg-6 d-flex align-items-center">
            <div class="section__wrapper text-align-justify">
              <div class="text-center">
                <h3 class="mt-5 mb-3 h1">Expense Management Software</h3>
              </div>
              <p>
                Simplify your expense tracking and financial management with our user-friendly software.
                Monitor real-time employee expenses, streamline budget management, and
                automate the approval and reimbursement process.
                Say goodbye to administrative hassles and hello to efficient expense management.
              </p>
            </div>
          </div>
          <div class="col-lg-6 image">
            <div class="section__wrapper mt-5">
              <div class="h-auto w-100">
                <img src="/assets/home.png" alt="expense-home" class="object-fit-cover w-100">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="wallet" class="section pt-5 pb-4">
      <div class="container max-width-1250">
        <div class="section__wrapper text-align-justify">
          <div class="row flex-md-row-reverse">
            <div class="col-lg-6 mt-5">
              <div class="text-center">
                <h3 class="mt-5 mb-3 h2">Do you want your wallet to be sad?</h3>
              </div>
              <div class="text-align-justify">
                <p class="mt-5">
                  Join us now and let's turn those tears into smiles with our money-saving tips.
                  Our powerful expense management app is here to help you take control of your finances,
                  so you can live life to the fullest without worrying about your wallet.
                  Say goodbye to financial stress and hello to financial freedom!
                <h4>Start your journey to a brighter financial future with us today.</h4>
                </p>
              </div>
            </div>
            <div class="col-lg-6 image">
              <div class="h-auto w-100">
                <img src="/assets/wallet.png" alt="expense-wallet" class="object-fit-cover w-100">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="workdy" class="section pt-5 pb-5 d-flex align-items-center justify-content-center">
      <div class="container max-width-1250 pt-5 pb-5">
        <div class="section__wrapper  pb-5">
          <div class="text-center pt-5">
            <h3 class="mt-5 mb-3 h1">Spend Smart</h3>
          </div>
          <div class="row pt-4">
            <div class="col-md-4">
              <div class="text-black shadow-lg mb-4 text-center box-wk rounded p-4">
                <h2 class="item-tit">Efficient spending</h2>
                <div class="item_txt">
                  <p>Optimize your expenses with our application, which is 4x more efficient than others, thanks to our
                    advanced expense management algorithm.</p>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div>
                <div class="text-black shadow-lg mb-4 text-center box-wk rounded p-4">
                  <h2 class="item-tit">Minimalistic look</h2>
                  <div class="item_txt">
                    <p>Experience a sleek and intuitive design based on the latest design principles, making our
                      application easy to use and visually pleasing.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="text-black shadow-lg mb-4 text-center box-wk rounded p-4">
                <h2 class="item-tit">Safer than ever</h2>
                <div class="item_txt">
                  <p>Rest assured knowing that our application utilizes state-of-the-art security technologies to
                    safeguard your data.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="about-us" class="section pt-5 pb-5">
      <div class="container max-width-1250">
        <div class="section__wrapper">
          <div class="row align-items-center" style="margin: 10% 0;">
            <div class="col-lg-6">
              <div class="text-center">
                <h3 class="mt-5 mb-3 h1">About Us</h3>
              </div>
              <div class="text-align-justify">
                <p>
                  At Expense Flow, we are more than just a financial management platform. We are your partners in
                  achieving financial success. Our mission is to empower you to take control of your finances and turn
                  your financial goals into a reality.
                </p>
                <p>
                  With a commitment to transparency, innovation, and user satisfaction, we've developed a cutting-edge
                  application designed to simplify the complexities of financial management. Whether you're budgeting,
                  saving, or planning for the future, we provide the tools and insights you need to make informed
                  decisions.
                </p>
                <p>
                  Join us on this journey towards financial empowerment. Let's build a future where financial freedom is
                  within everyone's reach.
                </p>
              </div>
            </div>
            <div class="col-lg-6 image text-center">
              <div class="h-auto w-100">
                <img src="/assets/about-us.png" alt="expense-about-us" class="object-fit-cover w-100">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section id="contacts" class="section pt-4 pb-5">
      <div class="container max-width-1250">
        <div class="section__wrapper">
          <div class="row flex-md-row-reverse" style="margin: 10% 0;">
            <div class="col-lg-6">
              <div class="text-center">
                <h3 class="mt-5 mb-3 h1">Contacts</h3>
              </div>
              <div class="text-align-justify">
                <p>
                  We're always here to help! Feel free to reach out to us anytime using the contact information below.
                  We pride ourselves on our prompt and responsive customer service. You can expect a quick reply to your
                  inquiries.
                  Whether you have questions about our software or need assistance with a specific issue,
                  we're here to help. Don't hesitate to get in touch! We're always happy to hear from you.
                </p>
                <a class="contactsBTN text-decoration-none rounded px-2 py-1" href="mailto:email@example.com"
                  class="contactsBTN p-1 mt-5 text-decoration-none">
                  <span>Contact us</span>
                </a>
              </div>
            </div>
            <div class="col-lg-6 image">
              <div class="h-auto w-100">
                <img src="/assets/contacts.png" alt="expense-contacts" class="object-fit-cover w-100">
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer class="py-2">
    <div class="Navfooter py-5">
      <div class="socialIcons display-flex justify-content-center">
        <a class="rounded-circle m-2 p-2" href=""><i class="fa-brands fa-facebook"></i></a>
        <a class="rounded-circle m-2 p-2" href="https://github.com/diogoPinheiro11/php-project"><i
            class="fa-brands fa-github"></i></a>
        <a class="rounded-circle m-2 p-2" href=""><i class="fa-brands fa-instagram"></i></a>
        <a class="rounded-circle m-2 p-2" href=""><i class="fa-brands fa-twitter"></i></a>
      </div>
      <ul class="display-flex justify-content-center text-center py-2">
        <li class="mt-2"><a class="text-decoration-none m-2" href="#home">Home</a></li>
        <li class="mt-2"><a class="text-decoration-none m-2" href="#about-us">About us</a></li>
        <li class="mt-2"><a class="text-decoration-none m-2" href="#workdy">Work Dynamics</a></li>
        <li class="mt-2"><a class="text-decoration-none m-2" href="#contacts">Contacts</a></li>
      </ul>
    </div>
    <div class="Footerbar text-center px-3">
      <p>&copy; 2023 Expense Flow</p>
      <p>All rights reserved. The 'Expense Flow' name and logo are trademarks of Expense Flow, Inc.</p>
    </div>
  </footer>
</body>

</html>