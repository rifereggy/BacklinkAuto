const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const logger = require('./utils/logger');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(helmet());
app.use(cors());
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true }));

// Health check endpoint
app.get('/health', (req, res) => {
  res.json({
    status: 'ok',
    timestamp: new Date().toISOString(),
    service: 'backlinkforge-automation'
  });
});

// Webhook endpoint for Laravel
app.post('/webhook/job-complete', (req, res) => {
  try {
    const { job_id, status, result, error } = req.body;
    
    logger.info('Job completed', {
      job_id,
      status,
      result: result ? 'success' : 'failed',
      error: error || null
    });

    // TODO: Send result back to Laravel
    res.json({ success: true, message: 'Webhook received' });
  } catch (error) {
    logger.error('Webhook error', { error: error.message });
    res.status(500).json({ success: false, error: error.message });
  }
});

// Automation endpoints
app.post('/automation/create-account', async (req, res) => {
  try {
    const { provider, credentials, proxy } = req.body;
    
    logger.info('Creating account', { provider, proxy: proxy ? 'with proxy' : 'no proxy' });
    
    // TODO: Implement account creation logic
    const result = {
      success: true,
      account_id: 'temp-account-123',
      url: 'https://example.com/account'
    };
    
    res.json(result);
  } catch (error) {
    logger.error('Account creation error', { error: error.message });
    res.status(500).json({ success: false, error: error.message });
  }
});

app.post('/automation/post-content', async (req, res) => {
  try {
    const { account_id, content, links } = req.body;
    
    logger.info('Posting content', { account_id, links_count: links?.length || 0 });
    
    // TODO: Implement content posting logic
    const result = {
      success: true,
      post_id: 'temp-post-123',
      url: 'https://example.com/post'
    };
    
    res.json(result);
  } catch (error) {
    logger.error('Content posting error', { error: error.message });
    res.status(500).json({ success: false, error: error.message });
  }
});

// Error handling middleware
app.use((error, req, res, next) => {
  logger.error('Unhandled error', { error: error.message, stack: error.stack });
  res.status(500).json({ success: false, error: 'Internal server error' });
});

// 404 handler
app.use('*', (req, res) => {
  res.status(404).json({ success: false, error: 'Endpoint not found' });
});

// Start server
app.listen(PORT, () => {
  logger.info(`Automation service started on port ${PORT}`);
});

module.exports = app; 