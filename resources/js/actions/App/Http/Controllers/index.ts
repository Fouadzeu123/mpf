import Auth from './Auth'
import MemberPortalController from './MemberPortalController'
import PaymentController from './PaymentController'
import WebhookController from './WebhookController'
import DashboardController from './DashboardController'
import MemberController from './MemberController'
import VisitorController from './VisitorController'
import ScannerController from './ScannerController'
import AttendanceController from './AttendanceController'
import CommunionController from './CommunionController'
import PrintController from './PrintController'
import Settings from './Settings'
const Controllers = {
    Auth: Object.assign(Auth, Auth),
MemberPortalController: Object.assign(MemberPortalController, MemberPortalController),
PaymentController: Object.assign(PaymentController, PaymentController),
WebhookController: Object.assign(WebhookController, WebhookController),
DashboardController: Object.assign(DashboardController, DashboardController),
MemberController: Object.assign(MemberController, MemberController),
VisitorController: Object.assign(VisitorController, VisitorController),
ScannerController: Object.assign(ScannerController, ScannerController),
AttendanceController: Object.assign(AttendanceController, AttendanceController),
CommunionController: Object.assign(CommunionController, CommunionController),
PrintController: Object.assign(PrintController, PrintController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers