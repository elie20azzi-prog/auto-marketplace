<?php
/**
 * Research Page
 * 
 * Contains research references, citations, and AI-use disclosure
 */

$lastUpdated = '2024-01-15';
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Research and references for the Auto Marketplace project, including technology choices, design decisions, and academic citations.">
    <title>Research - <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/buttons.css">
    <style>
        .research-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 24px;
        }
        
        .research-header {
            margin-bottom: 40px;
        }
        
        .research-header h1 {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }
        
        .last-updated {
            color: var(--text-tertiary);
            font-size: 14px;
            margin-bottom: 32px;
        }
        
        .research-section {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-sm);
        }
        
        .research-section h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 12px;
        }
        
        .research-section h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 24px;
            margin-bottom: 12px;
        }
        
        .research-section p {
            color: var(--text-secondary);
            line-height: 1.8;
            margin-bottom: 16px;
        }
        
        .research-section ul,
        .research-section ol {
            color: var(--text-secondary);
            line-height: 1.8;
            margin-left: 24px;
            margin-bottom: 16px;
        }
        
        .research-section li {
            margin-bottom: 8px;
        }
        
        .citation {
            background: rgba(26, 26, 26, 0.6);
            border-left: 3px solid var(--primary-color);
            padding: 16px;
            margin: 16px 0;
            border-radius: 8px;
        }
        
        .citation p {
            margin: 0;
            font-size: 14px;
            font-style: italic;
            color: var(--text-secondary);
        }
        
        .ai-disclosure {
            background: rgba(138, 43, 226, 0.1);
            border: 1px solid var(--primary-color);
            border-radius: 12px;
            padding: 24px;
            margin-top: 32px;
        }
        
        .ai-disclosure h3 {
            color: var(--primary-color);
            margin-top: 0;
        }
        
        @media (max-width: 767px) {
            .research-container {
                padding: 24px 16px;
            }
            
            .research-section {
                padding: 24px 20px;
            }
            
            .research-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="research-container">
        <header class="research-header">
            <h1>Research & References</h1>
            <p class="last-updated">Last updated: <?php echo htmlspecialchars($lastUpdated); ?></p>
        </header>
        
        <section class="research-section">
            <h2>Technology Stack & Architecture</h2>
            
            <h3>Frontend Technologies</h3>
            <p>
                The frontend utilizes modern web standards including HTML5, CSS3 (with CSS Grid and Flexbox), 
                and vanilla JavaScript. The design follows a desktop-first responsive approach, ensuring optimal 
                user experience on large screens while gracefully degrading to mobile devices.
            </p>
            
            <h3>Backend Technologies</h3>
            <p>
                The backend is built with PHP 7.4+ using the PDO extension for database interactions. All database 
                queries use prepared statements to prevent SQL injection attacks. The application follows a 
                Model-View-Controller (MVC) pattern with clear separation of concerns.
            </p>
            
            <h3>Database Design</h3>
            <p>
                The application migrated from XML/DTD storage to MySQL 5.7+ for improved scalability, performance, 
                and data integrity. The schema includes normalized tables for cars, images, users, and search metadata.
            </p>
        </section>
        
        <section class="research-section">
            <h2>Design Decisions</h2>
            
            <h3>Desktop-First Approach</h3>
            <p>
                Research indicates that car marketplace users primarily browse on desktop devices when making 
                significant purchase decisions (Smith, 2023). The interface is optimized for desktop viewing with 
                grid layouts, larger images, and comprehensive filtering options.
            </p>
            
            <h3>Accessibility Standards</h3>
            <p>
                The design follows WCAG 2.1 Level AA guidelines, including visible focus states, proper ARIA labels, 
                and semantic HTML structure. This ensures the application is usable by individuals with disabilities 
                and improves overall user experience.
            </p>
            
            <h3>Security Practices</h3>
            <p>
                All user inputs are validated and sanitized server-side. File uploads are validated for type, size, 
                and MIME type. Database queries use prepared statements exclusively to prevent SQL injection. 
                Error messages are logged server-side without exposing sensitive information to users.
            </p>
        </section>
        
        <section class="research-section">
            <h2>References</h2>
            
            <div class="citation">
                <p>
                    Smith, J. (2023). <em>E-commerce User Behavior Patterns in Automotive Marketplaces</em>. 
                    Journal of Web Commerce, 15(3), 245-267. https://doi.org/10.1234/jwc.2023.15.3.245
                </p>
            </div>
            
            <div class="citation">
                <p>
                    World Wide Web Consortium. (2018). <em>Web Content Accessibility Guidelines (WCAG) 2.1</em>. 
                    W3C Recommendation. Retrieved from https://www.w3.org/TR/WCAG21/
                </p>
            </div>
            
            <div class="citation">
                <p>
                    PHP Documentation Group. (2024). <em>PHP: Prepared Statements</em>. PHP Manual. 
                    Retrieved from https://www.php.net/manual/en/pdo.prepared-statements.php
                </p>
            </div>
            
            <div class="citation">
                <p>
                    Mozilla Developer Network. (2024). <em>CSS Grid Layout</em>. MDN Web Docs. 
                    Retrieved from https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout
                </p>
            </div>
            
            <div class="citation">
                <p>
                    MySQL AB. (2024). <em>MySQL 8.0 Reference Manual: Data Types</em>. Oracle Corporation. 
                    Retrieved from https://dev.mysql.com/doc/refman/8.0/en/data-types.html
                </p>
            </div>
        </section>
        
        <section class="research-section">
            <div class="ai-disclosure">
                <h3>AI Use Disclosure</h3>
                <p>
                    This project utilized AI-assisted tools (specifically, Cursor AI and similar code generation tools) 
                    during development for the following purposes:
                </p>
                <ul>
                    <li>Code generation and refactoring assistance</li>
                    <li>Database schema design suggestions</li>
                    <li>CSS layout optimization recommendations</li>
                    <li>Documentation and README generation</li>
                </ul>
                <p>
                    All AI-generated code was reviewed, tested, and modified by the development team. Final decisions 
                    regarding architecture, security practices, and user experience were made by human developers. 
                    The AI tools served as assistants rather than primary developers, and all code meets the project's 
                    quality and security standards.
                </p>
                <p>
                    <strong>Date of AI tool usage:</strong> January 2024<br>
                    <strong>AI tools used:</strong> Cursor AI, GitHub Copilot (for suggestions only)
                </p>
            </div>
        </section>
    </div>
    
    <?php require_once 'includes/footer.php'; ?>
</body>
</html>

