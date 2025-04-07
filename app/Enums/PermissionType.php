<?php

namespace App\Enums;

enum PermissionType: string
{
    // Batch
    case VIEW_BATCH         = 'view-batch';
    case CREATE_BATCH       = 'create-batch';
    case EDIT_BATCH         = 'edit-batch';
    case DELETE_BATCH       = 'delete-batch';
    case RESTORE_BATCH      = 'restore-batch';
    case FORCE_DELETE_BATCH = 'force-delete-batch';

    // Checklist
    case VIEW_CHECKLIST         = 'view-checklist';
    case CREATE_CHECKLIST       = 'create-checklist';
    case EDIT_CHECKLIST         = 'edit-checklist';
    case DELETE_CHECKLIST       = 'delete-checklist';
    case RESTORE_CHECKLIST      = 'restore-checklist';
    case FORCE_DELETE_CHECKLIST = 'force-delete-checklist';

    // ChecklistItem
    case VIEW_CHECKLIST_ITEM         = 'view-checklist-item';
    case CREATE_CHECKLIST_ITEM       = 'create-checklist-item';
    case EDIT_CHECKLIST_ITEM         = 'edit-checklist-item';
    case DELETE_CHECKLIST_ITEM       = 'delete-checklist-item';
    case RESTORE_CHECKLIST_ITEM      = 'restore-checklist-item';
    case FORCE_DELETE_CHECKLIST_ITEM = 'force-delete-checklist-item';

    // Document
    case VIEW_DOCUMENT         = 'view-document';
    case CREATE_DOCUMENT       = 'create-document';
    case EDIT_DOCUMENT         = 'edit-document';
    case DELETE_DOCUMENT       = 'delete-document';
    case RESTORE_DOCUMENT      = 'restore-document';
    case FORCE_DELETE_DOCUMENT = 'force-delete-document';

    // DocumentCategory
    case VIEW_DOCUMENT_CATEGORY         = 'view-document-category';
    case CREATE_DOCUMENT_CATEGORY       = 'create-document-category';
    case EDIT_DOCUMENT_CATEGORY         = 'edit-document-category';
    case DELETE_DOCUMENT_CATEGORY       = 'delete-document-category';
    case RESTORE_DOCUMENT_CATEGORY      = 'restore-document-category';
    case FORCE_DELETE_DOCUMENT_CATEGORY = 'force-delete-document-category';

    // EnvironmentalRecord
    case VIEW_ENVIRONMENTAL_RECORD         = 'view-environmental-record';
    case CREATE_ENVIRONMENTAL_RECORD       = 'create-environmental-record';
    case EDIT_ENVIRONMENTAL_RECORD         = 'edit-environmental-record';
    case DELETE_ENVIRONMENTAL_RECORD       = 'delete-environmental-record';
    case RESTORE_ENVIRONMENTAL_RECORD      = 'restore-environmental-record';
    case FORCE_DELETE_ENVIRONMENTAL_RECORD = 'force-delete-environmental-record';

    // EvaluationRecord
    case VIEW_EVALUATION_RECORD         = 'view-evaluation-record';
    case CREATE_EVALUATION_RECORD       = 'create-evaluation-record';
    case EDIT_EVALUATION_RECORD         = 'edit-evaluation-record';
    case DELETE_EVALUATION_RECORD       = 'delete-evaluation-record';
    case RESTORE_EVALUATION_RECORD      = 'restore-evaluation-record';
    case FORCE_DELETE_EVALUATION_RECORD = 'force-delete-evaluation-record';

    // ImprovementPlan
    case VIEW_IMPROVEMENT_PLAN         = 'view-improvement-plan';
    case CREATE_IMPROVEMENT_PLAN       = 'create-improvement-plan';
    case EDIT_IMPROVEMENT_PLAN         = 'edit-improvement-plan';
    case DELETE_IMPROVEMENT_PLAN       = 'delete-improvement-plan';
    case RESTORE_IMPROVEMENT_PLAN      = 'restore-improvement-plan';
    case FORCE_DELETE_IMPROVEMENT_PLAN = 'force-delete-improvement-plan';

    // Inventory
    case VIEW_INVENTORY         = 'view-inventory';
    case CREATE_INVENTORY       = 'create-inventory';
    case EDIT_INVENTORY         = 'edit-inventory';
    case DELETE_INVENTORY       = 'delete-inventory';
    case RESTORE_INVENTORY      = 'restore-inventory';
    case FORCE_DELETE_INVENTORY = 'force-delete-inventory';

    // Invoice
    case VIEW_INVOICE         = 'view-invoice';
    case CREATE_INVOICE       = 'create-invoice';
    case EDIT_INVOICE         = 'edit-invoice';
    case DELETE_INVOICE       = 'delete-invoice';
    case RESTORE_INVOICE      = 'restore-invoice';
    case FORCE_DELETE_INVOICE = 'force-delete-invoice';

    // Manufacturer
    case VIEW_MANUFACTURER         = 'view-manufacturer';
    case CREATE_MANUFACTURER       = 'create-manufacturer';
    case EDIT_MANUFACTURER         = 'edit-manufacturer';
    case DELETE_MANUFACTURER       = 'delete-manufacturer';
    case RESTORE_MANUFACTURER      = 'restore-manufacturer';
    case FORCE_DELETE_MANUFACTURER = 'force-delete-manufacturer';

    // Patient
    case VIEW_PATIENT         = 'view-patient';
    case CREATE_PATIENT       = 'create-patient';
    case EDIT_PATIENT         = 'edit-patient';
    case DELETE_PATIENT       = 'delete-patient';
    case RESTORE_PATIENT      = 'restore-patient';
    case FORCE_DELETE_PATIENT = 'force-delete-patient';

    // PharmaceuticalForm
    case VIEW_PHARMACEUTICAL_FORM         = 'view-pharmaceutical-form';
    case CREATE_PHARMACEUTICAL_FORM       = 'create-pharmaceutical-form';
    case EDIT_PHARMACEUTICAL_FORM         = 'edit-pharmaceutical-form';
    case DELETE_PHARMACEUTICAL_FORM       = 'delete-pharmaceutical-form';
    case RESTORE_PHARMACEUTICAL_FORM      = 'restore-pharmaceutical-form';
    case FORCE_DELETE_PHARMACEUTICAL_FORM = 'force-delete-pharmaceutical-form';

    // Process
    case VIEW_PROCESS         = 'view-process';
    case CREATE_PROCESS       = 'create-process';
    case EDIT_PROCESS         = 'edit-process';
    case DELETE_PROCESS       = 'delete-process';
    case RESTORE_PROCESS      = 'restore-process';
    case FORCE_DELETE_PROCESS = 'force-delete-process';

    // ProcessType
    case VIEW_PROCESS_TYPE         = 'view-process-type';
    case CREATE_PROCESS_TYPE       = 'create-process-type';
    case EDIT_PROCESS_TYPE         = 'edit-process-type';
    case DELETE_PROCESS_TYPE       = 'delete-process-type';
    case RESTORE_PROCESS_TYPE      = 'restore-process-type';
    case FORCE_DELETE_PROCESS_TYPE = 'force-delete-process-type';

    // Product
    case VIEW_PRODUCT         = 'view-product';
    case CREATE_PRODUCT       = 'create-product';
    case EDIT_PRODUCT         = 'edit-product';
    case DELETE_PRODUCT       = 'delete-product';
    case RESTORE_PRODUCT      = 'restore-product';
    case FORCE_DELETE_PRODUCT = 'force-delete-product';

    // ProductCategory
    case VIEW_PRODUCT_CATEGORY         = 'view-product-category';
    case CREATE_PRODUCT_CATEGORY       = 'create-product-category';
    case EDIT_PRODUCT_CATEGORY         = 'edit-product-category';
    case DELETE_PRODUCT_CATEGORY       = 'delete-product-category';
    case RESTORE_PRODUCT_CATEGORY      = 'restore-product-category';
    case FORCE_DELETE_PRODUCT_CATEGORY = 'force-delete-product-category';

    // ProductReception
    case VIEW_PRODUCT_RECEPTION         = 'view-product-reception';
    case CREATE_PRODUCT_RECEPTION       = 'create-product-reception';
    case EDIT_PRODUCT_RECEPTION         = 'edit-product-reception';
    case DELETE_PRODUCT_RECEPTION       = 'delete-product-reception';
    case RESTORE_PRODUCT_RECEPTION      = 'restore-product-reception';
    case FORCE_DELETE_PRODUCT_RECEPTION = 'force-delete-product-reception';

    // ProductReceptionItem
    case VIEW_PRODUCT_RECEPTION_ITEM         = 'view-product-reception-item';
    case CREATE_PRODUCT_RECEPTION_ITEM       = 'create-product-reception-item';
    case EDIT_PRODUCT_RECEPTION_ITEM         = 'edit-product-reception-item';
    case DELETE_PRODUCT_RECEPTION_ITEM       = 'delete-product-reception-item';
    case RESTORE_PRODUCT_RECEPTION_ITEM      = 'restore-product-reception-item';
    case FORCE_DELETE_PRODUCT_RECEPTION_ITEM = 'force-delete-product-reception-item';

    // Purchase
    case VIEW_PURCHASE         = 'view-purchase';
    case CREATE_PURCHASE       = 'create-purchase';
    case EDIT_PURCHASE         = 'edit-purchase';
    case DELETE_PURCHASE       = 'delete-purchase';
    case RESTORE_PURCHASE      = 'restore-purchase';
    case FORCE_DELETE_PURCHASE = 'force-delete-purchase';

    // PurchaseItem
    case VIEW_PURCHASE_ITEM         = 'view-purchase-item';
    case CREATE_PURCHASE_ITEM       = 'create-purchase-item';
    case EDIT_PURCHASE_ITEM         = 'edit-purchase-item';
    case DELETE_PURCHASE_ITEM       = 'delete-purchase-item';
    case RESTORE_PURCHASE_ITEM      = 'restore-purchase-item';
    case FORCE_DELETE_PURCHASE_ITEM = 'force-delete-purchase-item';

    // Question
    case VIEW_QUESTION         = 'view-question';
    case CREATE_QUESTION       = 'create-question';
    case EDIT_QUESTION         = 'edit-question';
    case DELETE_QUESTION       = 'delete-question';
    case RESTORE_QUESTION      = 'restore-question';
    case FORCE_DELETE_QUESTION = 'force-delete-question';

    // QuestionOption
    case VIEW_QUESTION_OPTION         = 'view-question-option';
    case CREATE_QUESTION_OPTION       = 'create-question-option';
    case EDIT_QUESTION_OPTION         = 'edit-question-option';
    case DELETE_QUESTION_OPTION       = 'delete-question-option';
    case RESTORE_QUESTION_OPTION      = 'restore-question-option';
    case FORCE_DELETE_QUESTION_OPTION = 'force-delete-question-option';

    // Recipebook
    case VIEW_RECIPEBOOK         = 'view-recipebook';
    case CREATE_RECIPEBOOK       = 'create-recipebook';
    case EDIT_RECIPEBOOK         = 'edit-recipebook';
    case DELETE_RECIPEBOOK       = 'delete-recipebook';
    case RESTORE_RECIPEBOOK      = 'restore-recipebook';
    case FORCE_DELETE_RECIPEBOOK = 'force-delete-recipebook';

    // RecipebookItem
    case VIEW_RECIPEBOOK_ITEM         = 'view-recipebook-item';
    case CREATE_RECIPEBOOK_ITEM       = 'create-recipebook-item';
    case EDIT_RECIPEBOOK_ITEM         = 'edit-recipebook-item';
    case DELETE_RECIPEBOOK_ITEM       = 'delete-recipebook-item';
    case RESTORE_RECIPEBOOK_ITEM      = 'restore-recipebook-item';
    case FORCE_DELETE_RECIPEBOOK_ITEM = 'force-delete-recipebook-item';

    // Record
    case VIEW_RECORD         = 'view-record';
    case CREATE_RECORD       = 'create-record';
    case EDIT_RECORD         = 'edit-record';
    case DELETE_RECORD       = 'delete-record';
    case RESTORE_RECORD      = 'restore-record';
    case FORCE_DELETE_RECORD = 'force-delete-record';

    // Sale
    case VIEW_SALE         = 'view-sale';
    case CREATE_SALE       = 'create-sale';
    case EDIT_SALE         = 'edit-sale';
    case DELETE_SALE       = 'delete-sale';
    case RESTORE_SALE      = 'restore-sale';
    case FORCE_DELETE_SALE = 'force-delete-sale';

    // SaleItem
    case VIEW_SALE_ITEM         = 'view-sale-item';
    case CREATE_SALE_ITEM       = 'create-sale-item';
    case EDIT_SALE_ITEM         = 'edit-sale-item';
    case DELETE_SALE_ITEM       = 'delete-sale-item';
    case RESTORE_SALE_ITEM      = 'restore-sale-item';
    case FORCE_DELETE_SALE_ITEM = 'force-delete-sale-item';

    // SanitaryRegistry
    case VIEW_SANITARY_REGISTRY         = 'view-sanitary-registry';
    case CREATE_SANITARY_REGISTRY       = 'create-sanitary-registry';
    case EDIT_SANITARY_REGISTRY         = 'edit-sanitary-registry';
    case DELETE_SANITARY_REGISTRY       = 'delete-sanitary-registry';
    case RESTORE_SANITARY_REGISTRY      = 'restore-sanitary-registry';
    case FORCE_DELETE_SANITARY_REGISTRY = 'force-delete-sanitary-registry';

    // Supplier
    case VIEW_SUPPLIER         = 'view-supplier';
    case CREATE_SUPPLIER       = 'create-supplier';
    case EDIT_SUPPLIER         = 'edit-supplier';
    case DELETE_SUPPLIER       = 'delete-supplier';
    case RESTORE_SUPPLIER      = 'restore-supplier';
    case FORCE_DELETE_SUPPLIER = 'force-delete-supplier';

    // Task
    case VIEW_TASK         = 'view-task';
    case CREATE_TASK       = 'create-task';
    case EDIT_TASK         = 'edit-task';
    case DELETE_TASK       = 'delete-task';
    case RESTORE_TASK      = 'restore-task';
    case FORCE_DELETE_TASK = 'force-delete-task';

    // Team
    case VIEW_TEAM         = 'view-team';
    case CREATE_TEAM       = 'create-team';
    case EDIT_TEAM         = 'edit-team';
    case DELETE_TEAM       = 'delete-team';
    case RESTORE_TEAM      = 'restore-team';
    case FORCE_DELETE_TEAM = 'force-delete-team';

    // TeamProductPrice
    case VIEW_TEAM_PRODUCT_PRICE         = 'view-team-product-price';
    case CREATE_TEAM_PRODUCT_PRICE       = 'create-team-product-price';
    case EDIT_TEAM_PRODUCT_PRICE         = 'edit-team-product-price';
    case DELETE_TEAM_PRODUCT_PRICE       = 'delete-team-product-price';
    case RESTORE_TEAM_PRODUCT_PRICE      = 'restore-team-product-price';
    case FORCE_DELETE_TEAM_PRODUCT_PRICE = 'force-delete-team-product-price';

    // Training
    case VIEW_TRAINING         = 'view-training';
    case CREATE_TRAINING       = 'create-training';
    case EDIT_TRAINING         = 'edit-training';
    case DELETE_TRAINING       = 'delete-training';
    case RESTORE_TRAINING      = 'restore-training';
    case FORCE_DELETE_TRAINING = 'force-delete-training';

    // TrainingCategory
    case VIEW_TRAINING_CATEGORY         = 'view-training-category';
    case CREATE_TRAINING_CATEGORY       = 'create-training-category';
    case EDIT_TRAINING_CATEGORY         = 'edit-training-category';
    case DELETE_TRAINING_CATEGORY       = 'delete-training-category';
    case RESTORE_TRAINING_CATEGORY      = 'restore-training-category';
    case FORCE_DELETE_TRAINING_CATEGORY = 'force-delete-training-category';

    // User
    case VIEW_USER         = 'view-user';
    case CREATE_USER       = 'create-user';
    case EDIT_USER         = 'edit-user';
    case DELETE_USER       = 'delete-user';
    case RESTORE_USER      = 'restore-user';
    case FORCE_DELETE_USER = 'force-delete-user';
}
