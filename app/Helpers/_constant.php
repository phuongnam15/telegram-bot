<?php
const SOURCE_MEDIA = "public/media";
const PATH_MEDIA = "media";
const KEY_AUTH_USER = 'user';
const KEY_AUTH_ADMIN = 'admin';
const DEFAULT_PAGINATE = 10;
const PASS_VALID_TIME = 60;

const BOT_TOKEN = "6618205269:AAFKAsIcFvHyYAD6RLitdIq1mmr-l3HocTc";

const ALLOW_UPDATE = [
    'message', // Nhận tin nhắn mới.
    'edited_message', // Nhận phiên bản chỉnh sửa của tin nhắn đã được bot biết đến.
    'channel_post', // Nhận bài đăng mới trong kênh.
    'edited_channel_post', // Nhận phiên bản chỉnh sửa của bài đăng trong kênh.
    'inline_query', // Nhận truy vấn nội tuyến mới.
    'chosen_inline_result', // Nhận kết quả truy vấn nội tuyến được người dùng chọn.
    'callback_query', // Nhận truy vấn callback mới.
    'shipping_query', // Nhận truy vấn vận chuyển mới (chỉ dành cho hóa đơn với giá linh hoạt).
    'pre_checkout_query', // Nhận truy vấn trước khi thanh toán (chứa thông tin chi tiết về thanh toán).
    'poll', // Nhận trạng thái mới của cuộc thăm dò ý kiến.
    'poll_answer', // Nhận câu trả lời của người dùng trong cuộc thăm dò ý kiến không ẩn danh.
    'my_chat_member', // Trạng thái thành viên của bot trong một cuộc trò chuyện được cập nhật.
    'chat_member', // Trạng thái của một thành viên trong một cuộc trò chuyện được cập nhật.
    'chat_join_request' // Nhận yêu cầu tham gia trò chuyện mới.
];
