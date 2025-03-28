// import { useEffect, useState } from 'react';
import React, { useState, useEffect } from 'react';

const Chat = ({ ticketId }) => {
  const [messages, setMessages] = useState([]);
  const [newMessage, setNewMessage] = useState('');

  useEffect(() => {
    const fetchMessages = async () => {
      try {
        const response = await fetch(`/api/responses/ticket/${ticketId}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('authToken')}`,
          },
        });
        if (!response.ok) throw new Error('Failed to fetch messages');
        const data = await response.json();
        setMessages(data);
        console.log(data);
        
      } catch (error) {
        console.error('Error fetching messages:', error);
      }
    };

    fetchMessages();
  }, [ticketId]);

  const sendMessage = async () => {
    if (!newMessage.trim()) return;

    try {
      const response = await fetch('/api/responses', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('authToken')}`,
        },
        body: JSON.stringify({
          ticket_id: ticketId,
          response: newMessage,
          user_id : localStorage.getItem('userId')
        }),
      });

      if (!response.ok) throw new Error('Failed to send message');

      const sentMessage = await response.json();
      setMessages((prev) => [...prev, sentMessage]);
      setNewMessage('');
    } catch (error) {
      console.error('Error sending message:', error);
    }
  };

  return (
    <div style={{ border: '1px solid #ccc', padding: '10px', borderRadius: '8px' }}>
      <h3>Chat</h3>

      <div style={{ maxHeight: '300px', overflowY: 'auto', marginBottom: '10px' }}>
        {messages.map((msg) => (
          <div key={msg.id} style={{ marginBottom: '5px' }}>
            <strong>{msg.user_name}:</strong> {msg.response}
          </div>
        ))}
      </div>

      <div style={{ display: 'flex' }}>
        <input
          type="text"
          value={newMessage}
          onChange={(e) => setNewMessage(e.target.value)}
          placeholder="Type a message..."
          style={{ flex: 1, padding: '5px' }}
        />
        <button onClick={sendMessage} style={{ marginLeft: '10px' }}>
          Send
        </button>
      </div>
    </div>
  );
};

export default Chat;
