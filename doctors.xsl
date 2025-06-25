<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" encoding="UTF-8"/>
  
  <xsl:template match="/doctors">
    <html>
      <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Our Medical Experts</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet"/>
        <style>
          :root {
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --secondary-color: #f8fafc;
            --text-color: #334155;
            --light-gray: #e2e8f0;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
          }
          
          body {
            font-family: 'Open Sans', sans-serif;
            color: var(--text-color);
            background-color: #f9fafb;
            line-height: 1.6;
            margin: 0;
            padding: 0;
          }
          
          h2 {
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: rgb(40, 40, 167);
            margin: 2rem 0 1rem;
            position: relative;
            padding-bottom: 1rem;
          }
          
          .doctor-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1000px;
            margin: 0 auto;
          }
          
          .doctor-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid var(--light-gray);
          }
          
          .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
          }
          
          .card-header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
          }
          
          .doctor-card h3 {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 20px;
            font-weight: 600;
          }
          
          .card-body {
            padding: 1.5rem;
          }
          
          .doctor-info {
            display: flex;
            margin-bottom: 1rem;
            align-items: flex-start;
          }
          
          .label {
            font-weight: 600;
            color: var(--primary-dark);
            min-width: 100px;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
          }
          
          .value {
            flex: 1;
            color: var(--text-color);
            font-size: 14px;
          }
          
          footer {
            text-align: center;
            padding: 2rem;
            background: var(--secondary-color);
            margin-top: 3rem;
            font-size: 0.9rem;
            color: #64748b;
          }
          
          @media (max-width: 768px) {
            .doctor-container {
              grid-template-columns: 1fr;
              padding: 1rem;
            }
            
            h2 {
              font-size: 2rem;
            }
          }
           @media (max-width: 580px) {
           h2{
                font-size:20px;
            }
            .doctor-card{
              font-size:18px;
            }
            .doctor-container{
              display:flex;
              flex-direction:column;
              align-items:center;
            }
            .card-header{
              padding:1rem;
            }
            .card-header h3{
              font-size:18px;
              padding:0px;
              margin:0px
            }
            .value{
              font-size: 12px;
            }
            .label{
              font-size:12px;
            }
            .pricing-info h1{
              padding:0px
            }
            .pricing-info p{
              font-size:12px;
              text-align: center;
              padding: 0 20px;
            }
          }
        </style>
      </head>
      <body>
        <h2>Our Medical Experts</h2>
        <div class="doctor-container">
          <xsl:for-each select="doctor">
            <div class="doctor-card">
              <div class="card-header">
                <h3><xsl:value-of select="name"/></h3>
              </div>
              <div class="card-body">
                <div class="doctor-info">
                  <span class="label">Specialty:</span>
                  <span class="value"><xsl:value-of select="specialty"/></span>
                </div>
                <div class="doctor-info">
                  <span class="label">Experience:</span>
                  <span class="value"><xsl:value-of select="experience"/> years</span>
                </div>
                <div class="doctor-info">
                  <span class="label">Email:</span>
                  <span class="value"><xsl:value-of select="email"/></span>
                </div>
                <div class="doctor-info">
                  <span class="label">Phone:</span>
                  <span class="value"><xsl:value-of select="phone"/></span>
                </div>
                <div class="doctor-info">
                  <span class="label">Work Hours:</span>
                  <span class="value"><xsl:value-of select="workhours"/></span>
                </div>
                <div class="doctor-info">
                  <span class="label">Location:</span>
                  <span class="value"><xsl:value-of select="location"/></span>
                </div>
              </div>
            </div>
          </xsl:for-each>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>